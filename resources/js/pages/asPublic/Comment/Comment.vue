<template>
    <div>
        <div class="box mt-4 mb-4 pb-4"
            v-if="user_has_login === false || isHasToken === ''">
            <p class="has-text-centered">
                to leave the comment,please 
                <router-link 
                    :to="{name:'LoginPage'}">login</router-link> 
                .
            </p>
        </div>
        <comment-form 
            :canComment="isHasToken" 
            :blog_id="b_id" 
            :editId="editId" 
            @openBox="openBox($event)" 
            @getComment="getComment($event)" 
            @pageNeedReload="pageNeedReload($event)"
            v-else></comment-form>

        <comment-list 
            :comments="comments" 
            :blog_id="blog_id" 
            @openBox="openBox($event)" 
            @getComment="getComment($event)"
            :isNoComment="isNoComment"></comment-list>

        <x-box :msg="res_status" 
            ref="xbox"></x-box>
    </div>
</template>
<script>
import CommentForm from "./CommentForm.vue"
import CommentList from "./CommentList.vue"

import xBox from "../../_include/BoxModal.vue"

export default{
    name:"PubComment",
    props:["blog_id","isHasToken"],
    components:{
        CommentList,
        CommentForm,
        xBox,
    },
    watch:{
        "blog_id":function(x){
            this.getWhatBlogId(x)
        }
    },
    data(){
        return{
            author:"",
            canComment:'',
            user_has_login:false,
            blog_title:"",
            perpage:10,
            comments:'',
            isNoComment:false,
            b_id:0,
            editId:0,
            res_status:'',
        }
    },
    mounted(){
       // this.getComment()
    },
    methods:{
        getComment(page){
            this.isNoComment = false
            let url = ""
            if(page){
                url = `${page}&blog_id=${this.b_id}&perpage=${this.perpage}`
                this.$cookies.set("oldpage_pub_comment",url)
            }
            url = this.$cookies.get("oldpage_pub_comment")
            if(!url) url = `/api/get-blog-comment?blog_id=${this.b_id}&perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{
                    //console.log(res.data)

                    // set the object of comments 
                    let comment_list = res.data.comments
                    if(Object.values(comment_list.data).length === 0){
                        this.isNoComment = true
                    }
                    // sent the comments object to comment list component
                    this.comments = comment_list

                    // set cookie old_blog_id only 
                    this.$cookies.set("old_blog_id",this.b_id)

                })
        },
        getWhatBlogId(x){
            this.b_id = x

            // check if the old blog id is in cookie
            if(this.$cookies.get("old_blog_id") && 
                this.$cookies.get("old_blog_id") !== x){
                // if old blog id not =  new blog id 
                // then remove the cookie
                this.$cookies.remove("old_blog_id")
              //  console.log(`the blog cookie is ${this.$cookies.get("old_blog_id")} 
              //  but the x is ${this.b_id}`)
            }
            this.getComment()
            this.user_has_login = window.lsDefault.user_has_login
        },
        edit(id){
            this.editId = id
        },
        openBox({msg,mytime = 0}){
            this.res_status = msg
            this.$refs.xbox.showBoxModal()
            if(mytime && mytime !== 0){
                setTimeout(()=>{
                    this.$refs.xbox.hideBoxModal()
                },mytime)
            }
        },
        pageNeedReload(){
            if(confirm("the page need to reload?") === true){
                location.reload()
            }else{
                this.res_status = ''
                this.getComment()
            }
        },
    },
}
</script>
