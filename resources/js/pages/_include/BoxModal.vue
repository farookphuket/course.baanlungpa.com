<template>
    <div>
        <!-- modal START -->
        <div class="modal" :class="{'is-active':isModalShow}">
            <div class="modal-background"></div>
            <div class="modal-content">
                <!-- Any other Bulma elements you want -->
                <div class="box">
                    <div v-html="msg"></div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" 
                @click.prevent="isModalShow = ''"></button>
        </div>
        <!-- modal END -->
    </div>
</template>
<script>
export default{
    name:"BoxModal",
    props:["msg"],
    data(){return{
        isModalShow:''
    }},
    methods:{
        showBoxModal(){
            this.isModalShow = 'is-active'
        },
        hideBoxModal(){
            this.isModalShow = ''
        },
    },

}
</script>
