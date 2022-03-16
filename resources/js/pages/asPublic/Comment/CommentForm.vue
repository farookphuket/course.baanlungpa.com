<template>
    <div>
        <div class="mt-4 mb-4 pb-4">
            <form action="">
                <div class="field">
                    <div class="control has-icons-right">
                        <input v-model="cForm.co_title" class="input" 
                        type="text" name="">
                        <span class="icon is-right">
                            {{cForm.co_title.length}}
                        </span>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <jodit-editor 
                            height=450 
                            v-model="cForm.co_body"></jodit-editor>
                    </div>
                    <div class="mt-4 pt-4">
                        <div class="mb-2 pb-5">
                            <div class="field is-pulled-right">
                                <span class="mr-4 tag is-rounded is-medium">
                                    {{cForm.co_body.length}}
                                </span>
                                <button class="button is-primary is-rounded 
                                    is-outlined is-small">
                                    <font-awesome-icon 
                                        icon="copy"></font-awesome-icon>
                                </button>
                            </div>
                        </div>
                        <div class="pt-2 control has-icons-right">
                            <textarea v-model="cForm.co_body" class="textarea" 
                                name="" ></textarea>
                            <span class="icon is-right is-large">
                                {{cForm.co_body.length}}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- status,button START -->
                <div class="mt-4 mb-4">
                    <div class="columns is-mobile">

                        <div class="column"><!-- div.column,status START -->
                            <div v-html="res_status"></div>

                        </div><!-- div.column,status END -->
                        <div class="column"><!-- button START -->
                            <div class="mb-4">
                                <div class="field is-pulled-right mr-2">

                                    <button class="button is-danger 
                                        is-outlined is-rounded mr-2" 
                                        >
                                        <font-awesome-icon 
                                            icon="times"></font-awesome-icon>
                                        <span class="ml-2">Cancel</span>
                                    </button>
                                    <button class="button is-primary 
                                        is-outlined is-rounded" 
                                        @click.prevent="saveComment(editId)" 
                                        type="submit">
                                        <font-awesome-icon 
                                            icon="check"></font-awesome-icon>
                                        <span class="ml-2">Create</span>
                                    </button>

                                </div>
                            </div>
                        </div><!-- div.column button END -->
                    </div><!-- div.columns.is-mobile END -->
                </div>
                <!-- status,button End -->

            </form>
        </div>
    </div>
</template>
<script>
import JoditEditor from "jodit-vue3"
export default{
    name:"CommentForm",
    props:["canComment","editId","blog_id"],
    data(){
        return{
            res_status:'',
            cForm:new Form({
                co_title:'',
                co_body:''
            }),
        }
    },
    methods:{
        saveComment(id){
            let url = `/api/member/comment`
            let fData = new FormData()
            fData.append("co_title",this.cForm.co_title)
            fData.append("co_body",this.cForm.co_body)
            fData.append("blog_id",this.blog_id)

            if(id && id !== 0){
                fData.append("_method","PUT")
                url = `/api/member/comment/${id}`
            }
            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                    //this.$emit('openBox',{msg:this.res_status,myTime:2500})
                    setTimeout(()=>{
                        this.res_status = ''
                        this.cForm.reset()
                        this.$emit('pageNeedReload')
                    },3200)
                })
                .catch(err=>{
                    this.res_status = `<span 
                    class="tag is-medium has-text-danger 
                    has-text-weight-bold">
${Object.values(err.response.data.errors).join()}
                        </span>`
                    this.$emit('openBox',{msg:this.res_status,mytime:3200})
                })
        },
    },
}
</script>
