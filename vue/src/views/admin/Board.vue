<template>
    <AdminLayout>
        <div class="containerList">
            <div class="team-container">
                <div class="board-name" >{{dataBoard.name}}</div>
                <div class="member" v-for="member in dataMember" :key="member.user_id" :title="member.first_name">
                    {{member.first_name}} <button @click.stop="deleteMember(member.user_id)">delete</button>
                </div>
                <button class="button" v-if="!showNewMember" @click.stop="showNewMember = true">+ Add member</button>

                <form action="" @submit.prevent="addMember()">
                    <input type="text" placeholder="Username" v-if="showNewMember" v-model="newMember" autofocus>
                </form>
                
            </div>
            <div class="card-container">
                <div class="content">
                    <List v-for="list in dataList" :key="list.id" :datalist="list" v-on:fetchList="fetchList" v-on:refreshBoard="refreshBoard" />
                    <div class="list button">
                        <a href="#" class="button" v-if="!showNewList" @click.prevent="showNewList = true">+ Add another list</a>

                        <form action="" @submit.prevent="addList()">
                            <input type="text" placeholder="Username" v-if="showNewList" v-model="newList" autofocus>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </AdminLayout>
</template>

<script>

import axios from 'axios'
import AdminLayout from '@/views/layouts/AdminLayout'
import List from '@/components/List'

export default {
    name:'Cards',

    created(){
        this.token = this.$route.params.token
        this.fetchBoardDetail()
        this.fetchList()
    },

    data(){
        return{
            dataBoard : {},
            dataMember : [],
            dataList : [],
            newMember : '',
            newList : '',
            showNewList : false,
            showNewMember: false,
        }
    },

    methods: {
        refreshBoard(){
            console.log("ini manggil fetch list")
            this.dataList = []
            this.fetchList()
        },

        async fetchBoardDetail(){
            await axios.get('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/' + this.token).then(({data})=>{
                this.dataBoard = data.board
                this.dataMember = data.members
            })
            
        },

        async addMember(){
            await axios.post('http://localhost:8000/v1/board/' + this.$route.params.board_id + '/member/' + this.token,{
                username : this.newMember,
                token : this.token
            }).then(()=>{
                this.showNewMember = false;
                this.newMember = '';
                this.fetchBoardDetail();
            })
        },
        
        async deleteMember(user_id){
            await axios.post('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/member/' + user_id + '/' + this.token,{
                '_method': 'DELETE',
            })
            .then(()=>{
                this.fetchBoardDetail();
            })
        },

        async fetchList(){
            await axios.get('http://localhost:8000/v1/board/'+ this.$route.params.board_id + '/list/' + this.token).then(({data})=>{
                this.dataList = data.list
            })
        },

        async addList(){
            //v1/board/{id}/list/{token}
            await axios.post('http://localhost:8000/v1/board/' + this.$route.params.board_id + '/list/' + this.token,{
                name : this.newList,
            }).then(()=>{
                this.showNewList = false;
                this.newList = '';
                this.fetchList();
            })
        },
    },

    components:{
        AdminLayout,
        List,
    },
}
</script>

<style scoped>

</style>