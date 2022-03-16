<template>
    <div>
        <div class="mt-2 mb-4 pb-4">
            <form action="">
                <div class="mb-4 mt-2">
                    <div class="field">
                        <div class="control">
                            <input v-model="aForm.d_title" class="input" 
                            type="text" name="" 
                            ref="d_title">
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="pl-2" 
                            v-if="aForm.d_title.length != 0">
                            {{theSlug.thaiSlug(aForm.d_title)}} 
                            <span class="tag is-medium is-rounded 
                            ml-2 has-text-danger has-text-weight-bold" 
                            v-if="aForm.d_title.length >= 81">
                                {{aForm.d_title.length}}
                            </span>
                            <span class="tag is-medium is-rounded 
                                ml-2" v-else>
                                {{aForm.d_title.length}}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mt-2 mb-2">
                    <div class="field">
                        <div class="control">
                            <jodit-editor height=450
                                v-model="aForm.d_body"></jodit-editor>
                        </div>
                    </div>

                    <div class="mt-2 mb-2 pb-4" 
                        v-if="aForm.d_body.length >= 10">

                        <div class="field is-pulled-right">
                            <span class="tag is-rounded is-info 
                                is-medium mr-8">
                                {{aForm.d_body.length}}
                            </span>
                            <button class="button is-primary is-rounded 
                                is-small is-outlined" 
                                   @click.prevent="copyMe">
                                <font-awesome-icon 
                                    icon="copy"></font-awesome-icon>
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 mb-4 pt-4">
                        <div class="field">
                            <div class="control">
                                <textarea v-model="aForm.d_body" class="textarea" 
                                    ref="d_body"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="columns is-mobile">
                        <div class="column is-3">
                            <div class="field">
                                <div v-html="res_status"></div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field pl-4">
                                <label class="checkbox" for="">
                                    <input v-model="aForm.d_is_public" 
                                    type="checkbox" name="">
                                    <span> is public</span>
                                </label>
                            </div>
                        </div>
                        <div class="column">
                            <div class="mt-2 mb-4 pb-4">
                                <div class="field is-pulled-right mr-4">
                                    <button class="button is-primary 
                                        is-outlined is-rounded" 
                                        type="submit" 
                                        @click.prevent="saveAbout(editId)">
                                        <font-awesome-icon 
                                            icon="check"></font-awesome-icon>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </form>
        </div>
    </div>
</template>
<script>
import JoditEditor from "jodit-vue3"
export default{
    name:"AboutForm",
    props:["editId"],
    data(){
        return{
            aForm:new Form({
                d_title:'',
                d_slug:'',
                d_is_public:'',
                d_body:'',
            }),
            res_status:'',
            theSlug:new CustomText(),
        }
    },
    watch:{
        "editId":function(x){
            this.getEditData(x)
        }
    },
    methods:{
        copyMe(){
            this.$refs.d_body.select()
            document.execCommand('copy')
        },
        getEditData(x){
            if(x && x !== 0){
                setTimeout(()=>{
                    this.$refs.d_title.focus()
                },2500)
                this.aForm.d_is_public = 0
                let url = `/api/admin/default/${x}/edit`
                axios.get(url)
                    .then(res=>{
                        let rData = res.data.page
                        //console.log(res.data)
                        this.aForm.d_title = rData.d_title
                        this.aForm.d_body = rData.d_body
                        if(rData.d_is_public !== 0){
                            this.aForm.d_is_public = true
                        }
                    })
            }
        },
        saveAbout(id){
            let url = `/api/admin/default`
            let fData = new FormData()
            if(this.aForm.d_is_public === false){
                this.aForm.d_is_public = 0
            }

            fData.append("d_title",this.aForm.d_title)
            fData.append("d_slug",this.theSlug.thaiSlug(this.aForm.d_title))
            fData.append("d_body",this.aForm.d_body)
            fData.append("d_is_public",this.aForm.d_is_public)
//            console.log(`the public is ${this.aForm.d_is_public} ha ha`)
            if(id && id !== 0){
                fData.append("_method","PUT")
                url = `/api/admin/default/${id}`
            }


            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                    setTimeout(()=>{
                        this.res_status = ''
                        this.$emit('formReset')
                    },2500)
                })
                .catch(err=>{
                    this.res_status = `
                    <span 
        class="tag is-medium has-text-weight-bold has-text-danger">
            ${Object.values(err.response.data.errors).join()}
                    </span>
                    `
                    this.$emit('openBox',{msg:this.res_status,myTime:2500})
                })
            
        },
    },
}
</script>
