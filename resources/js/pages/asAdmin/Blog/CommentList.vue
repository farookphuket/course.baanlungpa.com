<template>
    <div >
        <div class="box" 
            v-if="comment_count === 0">
            <p class="title">
                there is no comment
            </p>
        </div>
        <article class="box" 
                 v-for="c in comments.data" 
                 v-else>
             <p class="title">
                {{c.co_title}}
             </p>
             <p class="subtitle">
                 blog: 
                 <span v-for="b in c.blog">{{b.bl_title}}</span>
             </p>
             <div class="mb-4">
                 <div v-html="c.co_body"></div>
             </div>
             <p class="pb-4">
                 <span class="ml-2 mr-2">
                     <font-awesome-icon 
                         icon="user"></font-awesome-icon>
                 </span>
                 <span>{{c.user.name}}</span>
                 <span class="ml-4 mr-2">
                     <font-awesome-icon 
                         icon="calendar-week"></font-awesome-icon>
                 </span>
                 <span>
                     {{moment(c.created_at).format("YYYY-MM-DD HH:mm:ss")}}
                 </span>
                 <span class="ml-2">
                     {{moment(c.created_at).fromNow()}}
                 </span>

                 <span class="ml-4 mr-2">
                        reply :
                 </span>
                 <span class="tag">
                     {{c.reply.length}}
                 </span>
                 <div class="field is-pulled-right">
                     <button class="button is-primary" 
                             @click.prevent="showReplyForm({comment_id:c.id,
                             blog:c.blog})" 
                            v-if="isFormShow === false || 
                            current_id !== c.id" 
                             >reply</button>
                     <button class="button is-danger 
                         is-outlined" 
                         @click.prevent="hideReplyForm" 
                         v-else>
                         cancel
                     </button>

                 </div>
             </p>

             <div class="mb-4 pb-4 mt-3" 
                 v-if="replyFormItems.comment_id === c.id && 
                 replyFormItems.reply === true ">
                 <form action="">
                     <div class="field">
                         <div class="control has-icons-right">
                             <input v-model="rForm.re_title" class="input" 
                             type="text" name="">
                             <span class="icon is-right">
                                 {{rForm.re_title.length}}
                             </span>
                         </div>
                     </div>
                     <div class="field">
                         <jodit-editor 
                             height=450 
                             v-model="rForm.re_body"></jodit-editor>
                     </div>

                 </form>

             </div>
             <div class="pt-2 mt-2">
                 <div class="box" 
                     v-for="r in c.reply">
                     <p class="title">{{r.re_title}}</p>
                     <div class="mb-2 pb-2">
                         <div v-html="r.re_body"></div>
                     </div>
                     <div class="mt-2 pb-2 pt-2">
                         <p>
                             <span class="mr-2">
                                 <font-awesome-icon 
                                     icon="user"></font-awesome-icon>
                             </span>
                             <span>{{r.user.name}}</span>
                             <span class="ml-4 mr-2">
                                 <font-awesome-icon 
                                     icon="calendar-week"></font-awesome-icon>
                             </span>
                             <span>{{moment(r.created_at).fromNow()}}</span>
                         </p>
                     </div>
                 </div>
             </div>
        </article>
    </div>
</template>
<script>
var moment = require('moment')
import JoditEditor from "jodit-vue3"
export default{
    name:"CommentList",
    props:["comments","comment_count"],
    data(){
        return{
            moment:moment,
            replyFormItems:{},
            isFormShow:false,
            rForm:new Form({
                comment_id:'',
                blog_id:'',
                re_title:'',
                re_body:''
            }),
            res_status:'',
            current_id:0,
        }
    },
    methods:{
        showReplyForm(info){

            this.rForm.reset()
            let c_id = info.comment_id 
            let theBlog = info.blog
            this.current_id = c_id
            this.isFormShow = true
            this.replyFormItems = {
                comment_id:c_id,
                reply:true
            }
            this.rForm.comment_id = c_id
            theBlog.map((b)=>{
                this.rForm.blog_id = b.id
            })
        },
        hideReplyForm(){
            this.isFormShow = false 
            this.replyFormItems = {comment_id:0,reply:false}

        },

    },
}
</script>
