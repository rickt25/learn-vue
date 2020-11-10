<template>
    <div class="card">
            <div class="card-content" v-if="!showEditTask" @click.stop="tampilkanEdit">
                {{datacard.task}}
            </div>

            <div class="card-contents" v-if="showEditTask">
                 <form action="" @submit.prevent="updateCard(datacard.id)">
                    <input type="text" :placeholder="datacard.task" v-model="editTask">
                </form>
                <select v-model="listDropdown">
                    <option v-for="list in dataList" :key="list.id" :value="list.id">{{list.name}}</option>
                </select>
                <button class="cancelbtn" @click.stop="sembunyikanEdit">cancel</button>
                <button class="deletebtn" @click.stop="deleteCard(datacard.id)">delete</button>
            </div>

            <div class="control">
                <span @click.stop="moveUp(datacard.id)">&uarr;</span>
                <span @click.stop="moveDown(datacard.id)">&darr;</span>
            </div>
    </div>
</template>

<script>

import axios from 'axios'

export default {
    
    name:'Card',

    created(){
        this.token = this.$route.params.token
    },
    
    props:{
        datacard:{
            type: Object,
        }
    },

    data(){
        return{
            token: null,
            showEditTask: false,
            editTask : '',
            dataList : [],
            listDropdown : '',
        }
    },

    methods:{

        tampilkanEdit(){
            this.showEditTask = true
            this.fetchList()
        },

        sembunyikanEdit(){
            this.showEditTask = false
            this.dataList = []
        },
        
        async fetchList(){
            await axios.get('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/list/' + this.token).then(({data})=>{
                this.dataList = data.list
            })
        },

        async updateCard(card_id){          
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/card/' + card_id + '/' + this.token,{
                '_method': 'PUT',
                task: this.editTask,
            }).then(()=>{
                this.showEditTask = false;
                this.editTask = '';
                this.$emit('fetchCard');
            })
        },

        async deleteCard(card_id){
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/card/' + card_id + '/' + this.token,{
                '_method': 'DELETE',
            })
            .then(()=>{
                this.$emit('fetchCard');
            })
        },

        async moveUp(card_id){
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/card/' + card_id + '/up/' + this.token,{
                '_method': 'PUT',
            })
            .then(()=>{
                this.$emit('fetchCard');
            })
        },

        async moveDown(card_id){
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/card/' + card_id + '/down/' + this.token,{
                '_method': 'PUT',
            })
            .then(()=>{
                this.$emit('fetchCard');
            })
        },

        
    },

    watch:{
        async listDropdown(val){
            await axios.post('http://localhost:8000/v1/card/'+ this.datacard.id + '/move/' + val + '/' + this.token,{
                '_method': 'PUT',
            })
            .then(()=>{
                this.$emit('refreshList');
            })
        }
    }
}
</script>