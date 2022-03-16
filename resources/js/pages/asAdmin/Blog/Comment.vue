<template>
    <section class="body_content">
        <div class="container">
            <p class="title">the blog comment</p>

            <comment-list 
                :comments="comments" 
                :comment_count="comment_count"
                ></comment-list>
        </div>
        <x-box :msg="res_status"></x-box>
    </section>
</template>
<script>

import CommentList from "./CommentList.vue"
import xBox from "../../_include/BoxModal.vue"

export default{
    name:"AdminBlogComment",
    components:{
        CommentList,
        xBox,
    },
    data(){return{
        comments:'',
        comment_count:0,
        res_status:'',
        perpage:10,
    }},
    mounted(){
        this.getComment()
    },
    methods:{
        getComment(page){
            let url = ''
            if(page){
                url = `${page}&perpage=${this.perpage}`
                this.$cookies.set('ablog_comment_old_page',url)
            }
            url = this.$cookies.get('ablog_comment_old_page')
            if(!url) url = `/api/admin/blog-comment?perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{
                    let theComments = res.data.comments
                    this.comments = theComments
                    this.comment_count = Object.values(theComments.data).length
                })
        },
    },

}
</script>
