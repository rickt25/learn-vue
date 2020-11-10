<template>
    <AdminLayout>
        <div class="container">
            <div class="board-container">
                <p class="board-title">Boards</p>
                <div class="board-wrapper" v-for="(board,index) in daftarBoard" :key="index">

                    <div class="board new-board" v-if="currentIndex == index">
                        <form action="" @submit.prevent="updateBoard(board.id)">
                            <input class="editForm" type="text" v-model="boardName" :placeholder="board.name" required>
                            <a href="#" class="cancelbtn" @click.stop="currentIndex = -1">Cancel</a>
                        </form>
                    </div>

                    <div class="board" v-else>
                        <button class="boardlink" v-on:click="$router.push('/board/'+board.id+'/'+token)">{{ board.name }}</button>
                        <button class="editbtn" @click.stop="showEditBoard(index)">Edit</button>
                        <button class="deletebtn" @click.stop="deleteBoard(board.id)">Delete</button>
                    </div>

                </div>
                <div class="board-wrapper">
                    
                    <div class="board new-board" v-if="!showNewBoardForm">
                        <button @click.stop="showNewBoard">Create new board...</button>
                    </div>

                    <div class="board new-board" v-else>
                        <form action="" @submit.prevent="storeBoard">
                            <input type="text" v-model="boardName" placeholder="Board name" required>
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


export default ({
    name: 'Boards',

    created(){
        this.token = this.$route.params.token
        this.fetchBoard()
    },

    data(){
        return{
            token: null,
            showNewBoardForm: false,
            showEditBoardForm: false,
            currentIndex : -1,
            boardName: '',
            daftarBoard: [],
        }
    },

    methods:{

        showNewBoard(){
            this.showNewBoardForm = true;
        },

        showEditBoard(index){
            this.currentIndex = index;
            this.boardName = '';
        },

        async fetchBoard(){
            await axios.get('http://localhost:8000/v1/board/' + this.token).then(({data})=>{
                this.daftarBoard = data.board
            }).catch(error =>{
                if(error.response.status == 401){
                    this.$router.push('/');
                }
            })
        },

        async storeBoard(){
            await axios.post('http://localhost:8000/v1/board/' + this.token,{
                name : this.boardName,
            }).then(()=>{
                this.showNewBoardForm = false;
                this.boardName = '';
                this.fetchBoard();
            })
        },

        async updateBoard(id){
            await axios.post('http://localhost:8000/v1/board/'+ id + '/'  + this.token,{
                '_method': 'PUT',
                name : this.boardName,
            }).then(()=>{
                this.currentIndex = -1;
                this.fetchBoard();
            })
        },

        async deleteBoard(id){
            await axios.post('http://localhost:8000/v1/board/'+ id + '/' + this.token, {
                '_method': 'DELETE',
            })
            .then(()=>{
                this.fetchBoard();
            })
        }
    },

    components:{
        AdminLayout
    },

})
</script>

<style scoped>

</style>