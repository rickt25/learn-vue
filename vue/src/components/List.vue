<template>
    <div class="list">
        <header v-if="!showEditList">
            <button class="list-name" @click.stop="showEditList = true">{{datalist.name}}</button>
            <button class="deletebtn" @click.stop="deleteList(datalist.id)">Delete</button>
        </header>

        <header class="edit-list" v-if="showEditList" @click.stop="showEditList = true">
            <form action="" @submit.prevent="updateList(datalist.id)">
                <input type="text" :placeholder="datalist.name" v-model="editList">
            </form>
            <button class="cancelbtn" @click.stop="showEditList = false">cancel</button>
            
        </header>
        
        <div class="cards">
            <Card v-for="card in datacard" :key="card.id" :datacard="card" v-on:fetchCard='fetchCard' v-on:refreshList='refreshList' />
        </div>

        <div class="button" @click.stop="showTaskForm = true" v-if="!showTaskForm">+ Add new card</div>
        
        <div v-if="showTaskForm">
            <form @submit.prevent="addCard()">
                <input type="text" v-model="taskForm" placeholder="task name">
            </form>
            <button class="cancelbtn" @click.stop="showTaskForm = false">cancel</button>
        </div>
        

        <div class="control">
            <span @click.stop="moveLeft(datalist.id)">&larr;</span>
            <span @click.stop="moveRight(datalist.id)">&rarr;</span>
        </div>
    </div>
</template>

<script>

import axios from 'axios'
import Card from '@/components/Card'

export default {
    name:'List',

    created(){
        this.token = this.$route.params.token;
        this.fetchCard();
    },

    props:{
        datalist: {
            type: Object,
            required: true,
        },
    },

    data(){
        return{
            datacard: [],
            showTaskForm: false,
            taskForm: '',
            showEditList: false,
            editList: '',
        }
    },

    methods:{
        refreshList(){
            console.log("sampai")
            this.$emit('refreshBoard')
        },

        async fetchCard(){
            await axios.get('http://localhost:8000/v1/board/'+ this.datalist.id + '/card/' + this.$route.params.token)
            .then(({data})=>{
                this.datacard = data.card
            })
        },

        async addCard(){
            await axios.post('http://localhost:8000/v1/board/' + this.datalist.board_id + '/list/' + this.datalist.id + '/card/' + this.$route.params.token,{
                task : this.taskForm,
            }).then(()=>{
                this.fetchCard();
                this.showEditList = false,
                this.taskForm = '';
            })
        },

        async updateList(list_id){
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/list/' + list_id + '/' + this.token,{
                '_method': 'PUT',
                name : this.editList,
            }).then(()=>{
                this.showEditList = false;
                this.editList = '';
                this.$emit('fetchList');
            })
        },

        async deleteList(list_id){
            
            // console.log(list_id)
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/list/' + list_id + '/' + this.token,{
                '_method': 'DELETE',
            })
            .then(()=>{
                this.$emit('fetchList');
            })
        },

        async moveLeft(list_id){
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/list/' + list_id + '/left/' + this.token,{
                '_method': 'PUT',
            })
            .then(()=>{
                this.$emit('fetchList');
            })
        },

        async moveRight(list_id){
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/list/' + list_id + '/right/' + this.token,{
                '_method': 'PUT',
            })
            .then(()=>{
                this.$emit('fetchList');
            })
        }
    },

    components:{
        Card,
    }
}
</script>