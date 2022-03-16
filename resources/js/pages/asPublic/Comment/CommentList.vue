<template>
    <div>
        <div class="box" v-if="isNoComment">
            <p class="title has-text-centered">There is no comment yet!</p>
            <p class="subtitle" v-if="hasToken !== null || 
                user_has_login !== false">
                do you want the first comment? create one now.</p>
            <p>{{hasToken}}</p>
        </div>
        <article 
            class="pb-2 box content" 
            v-for="c in comments.data"
            v-else>
            <p class="title">
                {{c.co_title}} 
            </p>

            <p>
                <span class="mr-1">
                    <font-awesome-icon 
                        icon="calendar-week"></font-awesome-icon>
                </span>
                <span class="ml-2"> 
                    {{moment(c.created_at)
                    .format("YYYY-MMM-DD HH:mm:ss")}}
                </span>
                <span class="mr-2 ml-2"> 
                    {{moment(c.created_at)
                    .fromNow()}}
                </span>
            </p>
            <div class="mb-3">
                <div v-html="c.co_body"></div>
            </div>
            <div class="mt-4 mb-4 pb-6"> <!-- wrapper reply form -->
                <div class="columns">
                    <div class="column">

                        <div class="field is-pulled-right">
                            <p class="pb-4">
                                <span class="mr-2">
                                    <font-awesome-icon 
                                        icon="user"></font-awesome-icon>
                                </span>
                                <span class="mr-2">{{c.user.name}}</span>

                                <span class="ml-2 mr-1">
                                    <font-awesome-icon 
                                        icon="reply"></font-awesome-icon>
                                </span>
                                <span class="pr-2" 
                                    v-if="hasToken === null 
                                    || user_has_login === false">
                                    reply {{c.reply.length}}
                                </span>
                                <span class="has-text-success" 
                                    v-else>
                                    {{c.reply.length}}
                                    <a href="" 
                                        @click.prevent="showReplyForm(c.id)" 
                                        v-if="replyFormItems.showForm === false || 
                                        replyFormItems.comment_id !== c.id">
                                        reply
                                    </a>
                                    <a href="" 
                                        @click.prevent="hideReplyForm(c.id)" 
                                        v-else>
                                        <span class="ml-2 has-text-danger">
                                            <font-awesome-icon 
                                                icon="times"></font-awesome-icon>
                                        </span>
                                        <span>
                                            cancel
                                        </span>
                                             

                                    </a>
                                </span>
                            </p>
                        </div>

                    </div><!-- div.column use END -->
                </div><!-- div.columns END -->

                
                <div class="mt-4" 
                    v-if="hasToken !== null || user_has_login !== false">
                    <form action="" 
                        v-if="replyFormItems.comment_id === c.id && 
                        replyFormItems.showForm === true">
                    <div class="mb-4">
                        <div class="field">
                            <div class="control has-icons-right">
                                <input v-model="reForm.re_title" 
                                class="input" type="text" 
                                placeholder="Enter title...">
                                <span class="icon is-small is-right" 
                                      >{{reForm.re_title.length}}</span>

                            </div>
                        </div>
                    </div>
                    <div class="field mb-4">
                        <jodit-editor v-model="reForm.re_body" 
                            height=450></jodit-editor>
                    </div>
                    <div class="field">
                        <div class="control has-icons-right">
                            <textarea v-model="reForm.re_body" name="" 
                                class="textarea"></textarea>

                            <span class="icon is-right">
                                {{reForm.re_body.length}}
                            </span>
                        </div>

                    </div>
                    <div class="mt-2 mb-4">
                        <div class="field">
                            <div v-html="res_status"></div>
                        </div>
                        <div class="field is-pulled-right">
                            <button class="button is-primary 
                                is-outlined  is-rounded" 
                                @click.prevent="saveReply">
                                reply
                            </button>
                        </div>

                    </div>
                    </form>
                </div><!-- end of reply form -->
                <div class="box" v-else>
                    <p class="title has-text-centered">login to reply</p>
                </div>

                <!-- overlay -->
                <div class="bulma-overlay-mixin-parent">
                    <div class="bulma-overlay-mixin">
                        <p>reply to 
                            <span class="tag is-medium 
                                has-text-weight-bold has-text-info">
                                {{c.user.name}}
                            </span>
                    : </p>
                    </div>
                </div>
                <!-- reply list wrapper START -->
                <div class="mt-4">
                    <div v-for="re in c.reply" 
                        class="box pb-4">
                        <p class="title">{{re.re_title}}</p>
                        <div>
                            <div v-html="re.re_body"></div>
                        </div>
                        <div class="mt-4 pt-4">
                            <p>
                                <span class="ml-1">
                                    <font-awesome-icon 
                                        icon="user"></font-awesome-icon>
                                </span>
                                <span class="ml-2">
                                    {{re.user.name}}
                                </span>
                                <span class="mr-1 ml-3">
                                    <font-awesome-icon 
                                        icon="calendar-week"></font-awesome-icon>
                                </span>
                                <span>
                                    {{moment(re.created_at).format("YY-MM-DD")}}
                                </span>
                                <span class="ml-2">
                                    {{moment(re.created_at).fromNow()}}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- reply list wrapper END -->
            </div><!-- end of div.mb-4 wrapper -->

        </article>
    </div>
</template>
<script>
var moment = require("moment")
import JoditEditor from "jodit-vue3"
export default{
    name:"CommentList",
    props:["comments","isNoComment","blog_id"],
    data(){
        return{
            hasToken:'',
            user_has_login:false,
            moment:moment,
            reForm:new Form({
                blog_id:'',
                comment_id:'',
                re_title:"",
                re_body:"",
            }),
            replyFormItems:{commment_id:0,showForm:false},
            res_status:'',
            b_id:0,
        }
    },
    watch:{
        "blog_id":function(x){
            this.getBlogId(x)
        }
    },
    mounted(){
        this.isHasToken()

    },
    methods:{
        isHasToken(){
            this.hasToken = null
            if(this.$cookies.get("token") && 
                this.$cookies.get("token") !== null){
                this.hasToken = this.$cookies.get("token")
            }
            this.user_has_login = window.lsDefault.user_has_login
        },
        getBlogId(x){
            this.b_id = x
        },
        showReplyForm(id){
            //alert(`show id ${id}`)
            this.reForm.reset()

            this.replyFormItems = {
                comment_id:id,
                showForm:true
            }
            this.reForm.comment_id = id
            this.reForm.blog_id = this.b_id

        },
        hideReplyForm(id){
            if(this.replyFormItems.comment_id === id){
                this.replyFormItems = {
                    comment_id:0,
                    showForm:false
                }
                //this.reForm.comment_id = 0
                this.reForm.reset()
            }
        },
        saveReply(){
            let url = `/api/member/reply`
            let fData = new FormData()
            fData.append("comment_id",this.reForm.comment_id)
            fData.append("blog_id",this.reForm.blog_id)
            fData.append("re_title",this.reForm.re_title)
            fData.append("re_body",this.reForm.re_body)

            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                    this.$emit('getComment')
                    this.$emit('openBox',{msg:this.res_status,mytime:2500})

                    setTimeout(()=>{
                        this.clearForm()
                    },3200)

                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium 
                    has-text-weight-bold has-text-danger">
${Object.values(err.response.data.errors).join()}
                        </span>`
                    this.$emit('openBox',{msg:this.res_status,mytime:3200})
                })
        },
        clearForm(){

            this.res_status = ''

            if(confirm(`the page need to be refresh?`) !== true){
                this.reForm.reset()
                this.replyFormItems = {}
            }else{
                location.reload()
            }
        },
    },

}
</script>
