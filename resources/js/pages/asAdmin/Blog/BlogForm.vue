<template>
    <div class="pb-4 mb-4">
        <form action="">

            <div class="mt-2 mb-4">
                <div class="box">
                    <p class="title">
                        Title of your post.
                    </p>
                    <p class="subtitle">
                        text limit at 80 leters.
                    </p>
                </div>
                <div class="field">
                    <div class="control">
                        <input v-model="bForm.bl_title" class="input" type="text" 
                        ref="title" placeholder="Title..."
                        name="">
                    </div>
                </div>
                <div class="field" v-if="bForm.bl_title.length >= 2">
                    <p>
                        <span class="content has-text-info 
                            has-text-weight-bold">
                            {{slugText.thaiSlug(bForm.bl_title)}}
                        </span>
                        <span class="tag is-medium">
                            {{bForm.bl_title.length}}
                        </span>
                        <span class="tag is-medium has-text-danger 
                        has-text-weight-bold" 
                        v-if="bForm.bl_title.length >= 81">
                        Error! your title is too long.</span>
                    </p>
                </div>
            </div>


            <div class="mt-2 mb-4 pb-2">
                <div class="box">
                    <p class="title">
                        category
                    </p>
                    <p class="subtitle">
                        this will specify your content.
                    </p>
                </div>
                <div class="field">
                    <div class="select">
                        <select v-model="bForm.category" name="" 
                            @change.prevent="setCategory" 
                            ref="category">
                            <option value="">-- Select Category --</option>
                            <option :value="ca.id" v-for="ca in category">
                            {{ca.cat_name}}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-2 mb-2">
                <div class="box">
                    <p class="title">
                      excerpt
                    </p>
                    <p class="subtitle">
                        your text is limit at 1000 letters
                    </p>
                </div>

                <div class="field">
                    <div class="control">
                        <jodit-editor 
                            height=450
                            v-model="bForm.bl_excerpt" 
                            :editorOptions="options" 
                            ></jodit-editor>
                    </div>
                </div>
                <!-- jodit-vue3 still the problem on update show none data 
                    9-Feb-2022 -->
                    <div class="mt-4 mb-4">
                        <div class="field is-pulled-right">
                            <span class="mr-2 tag is-rounded">
                                {{bForm.bl_excerpt.length}}
                            </span>
                            <span class="mr-2 ml-2 tag has-text-danger 
                            has-text-weight-bold" 
                                v-if="bForm.bl_excerpt.length >= 1001">
                                Your excerpt s too long!
                            </span>
                            <button class="button is-rounded is-primary 
                                is-small" 
                                @click.prevent="copyExcerptCode" 
                                    v-if="bForm.bl_excerpt.length >= 5">
                                <font-awesome-icon 
                                    icon="copy"></font-awesome-icon>
                                <span class="ml-2">copy text</span>
                            </button>
                        </div>
                        <div class="field">
                            <div class="box">
                                <textarea class="textarea" 
                                ref="bl_excerpt"
                                v-model="bForm.bl_excerpt"></textarea>
                            </div>
                        </div>
                    </div>

                <!-- jodit-vue3 still the problem on update show none data 
                    9-Feb-2022 -->

            </div>
            <!-- end of excerpt text -->
            
            <!-- body text start -->
            <div class="mt-2 mb-2">
                <div class="box">
                    <p class="title">post content</p>
                    <p class="subtitle">
                        your text content here
                    </p>
                </div>

                <div class="field">
                    <div class="control">
                        <jodit-editor 
                            height=450
                            v-model="bForm.bl_body" 
                            :editorOptions="options" 
                            ></jodit-editor>
                    </div>
                </div>
                <!-- jodit-vue3 still the problem on update show none data 
                    9-Feb-2022 -->
                    <div class="mt-4 mb-4">
                        <div class="field is-pulled-right">
                            <span class="mr-2 tag is-rounded">
                                {{bForm.bl_body.length}}
                            </span>

                            <button class="button is-rounded is-primary 
                                is-small pr-4" 
                                @click.prevent="copyBodyCode" 
                                    v-if="bForm.bl_body.length >= 5">
                                <font-awesome-icon 
                                    icon="copy"></font-awesome-icon>
                                <span class="ml-2">copy text</span>
                            </button>
                        </div>
                        <div class="field">
                            <div class="box">
                                <textarea class="textarea" 
                                ref="bl_body"
                                v-model="bForm.bl_body"></textarea>
                            </div>
                        </div>
                    </div>

                <!-- jodit-vue3 still the problem on update show none data 
                    9-Feb-2022 -->

            </div>
            <!-- end of excerpt text -->



            <div class="mb-4 pb-4 pt-4">
                <div class="box">
                    <p class="title">select tag or create new one</p>
                </div>
                <div class="columns is-mobile">
                    <!-- show tag list START -->
                    <div class="column is-9">
                        <label for="" v-for="ta in tag" 
                            class="checkbox">
                            <input v-model="bForm.tag" type="checkbox" 
                            :value="ta.id"> 
                            <span class="ml-2 pr-2">{{ta.tag_name}}</span>
                        </label>
                    </div>
                    <!-- show tag list End -->
                    <div class="column">
                        <div class="field">
                            <div class="control pr-2">
                                <input v-model="bForm.new_tag" class="input" type="text" 
                                ref="new_tag" @keypress="showHelp1 = true"
                                @keydown.enter.prevent="saveTag"
                                name="" placeholder="New tag...">
                            </div>
                        </div>
                        <div class="field" 
                             v-if="bForm.new_tag.length >= 2">
                            <span  
                                class="tag is-medium has-text-info">
                                Hit enter to save</span>
                            <span class="ml-2 mr-2">
                               "{{bForm.new_tag}}" 
                            </span>
                            <span class="tag is-medium">
                                {{bForm.new_tag.length}}
                            </span>
                            <span class="tag has-text-danger  
                                has-text-weight-bold is-medium" 
                                  v-if="bForm.new_tag.length >= 19">
                                  Error! your tag is too long
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- End of div show tag wrapper -->

            <!-- show bl_is_public checkbox ,save button START -->
            <div class="mt-2 mb-2">
                <div class="box">
                    <p class="title">Public your post?</p>
                </div>
                <div class="columns is-mobile">
                    <div class="column">
                        <label class="checkbox" for="">
                            <input v-model="bForm.bl_is_public" 
                            type="checkbox" name="">
                            <span class="tag is-medium has-text-warning 
                            is-danger" 
                                v-if="bForm.bl_is_public === false">
                                Keep it private</span>
                            <span class="tag is-medium has-text-success 
                                has-text-weight-bold" v-else>
                                Public this post 
                            </span>
                        </label>
                    </div>
                    <div class="column">
                        <div class="field is-pulled-right">

                            <button class="button is-danger is-outlined 
                                is-rounded ml-2" 
                                @click.prevent="closeAndRefresh">
                                <font-awesome-icon 
                                    icon="times"></font-awesome-icon>
                                <span class="ml-2 is-uppercase">
                                    clear & close
                                </span>
                            </button>
                            <button class="button is-primary is-rounded 
                                is-outlined" 
                                type="submit"
                                @click.prevent="saveBlog(editId)">
                                <font-awesome-icon 
                                    icon="check"></font-awesome-icon>
                                <span class="ml-2" v-if="editId == 0">Create</span>
                                <span class="ml-2" v-else>Save</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <!-- show bl_is_public checkbox ,save button END -->

            <!-- show status under save button Start -->
            <div class="mt-2 mb-2 pb-6">
                <div class="field is-pulled-right">
                    <div v-html="res_status"></div>
                </div>
            </div>
            <!-- show status under save button END -->
        </form>
    </div>
</template>
<script>

import {JoditEditor} from "jodit-vue3"

export default{
    name:"BlogForm",
    props:["tag","category","editId"],
    components:{
        JoditEditor,
    },

    data(){
        return{

            res_status:'',
            slugText:new CustomText(),
            showHelp1:false,
            bForm:new Form({
                bl_title:'',
                bl_slug:'',
                bl_excerpt:'',
                bl_body:'',
                bl_is_public:true,
                category:'',
                new_tag:'',
                tag:[],
            }),
            options:{
                askBeforePasteHTML:false,
        //        disablePlugins:"powered-by-jodit",
            },
        }
    },
    watch:{
        "editId":function(x){
            this.getEditData(x)
        }
    },
    mounted(){
        this.isEditingMode()
    },
    methods:{
        getEditData(x){
            if(x && x !==  0){
                setTimeout(()=>{
                    this.$refs.title.focus()
                },700)
                this.bForm.category = 0
                this.bForm.tag = []
                this.bForm.bl_is_public = false
                let url = `/api/admin/blog/${x}/edit`
                axios.get(url)
                    .then(res=>{
                        //console.log(res.data)
                        let rData = res.data.blog
                        this.bForm.bl_title = rData.bl_title
                        this.bForm.bl_excerpt = rData.bl_excerpt
                        this.bForm.bl_body = rData.bl_body
                        if(rData.bl_is_public !== 0){
                            this.bForm.bl_is_public = true
                        }
                        rData.category.map((ca)=>{
                            //console.log(ca)
                            if(ca.pivot.blog_id === rData.id){
                                this.bForm.category = ca.pivot.category_id
                            } 
                        })

                        rData.tag.map((ta)=>{
                            //console.log(ta)
                            if(ta.pivot.blog_id === rData.id){
                                this.bForm.tag.push(ta.pivot.tag_id)
                            }

                        })
                    })
            }
        },
        isEditingMode(){
            // there will not be an editing mode on the first load 
            // so we have to delete if it a cookies of editId exist
            // remove the cookies at the first form load 
            if(this.$cookies.get("editId") && 
                this.$cookies.get("editId") !== 0){
                this.$cookies.remove("editId")
            }

        },
        saveBlog(id){
            if(this.formHasError() === true){
                return 
            }else{

            this.bForm.bl_slug = this.slugText.thaiSlug(this.bForm.bl_title)

            let fData = {
                bl_title:this.bForm.bl_title,
                bl_slug:this.bForm.bl_slug,
                bl_excerpt:this.bForm.bl_excerpt,
                bl_body:this.bForm.bl_body,
                bl_is_public:this.bForm.bl_is_public,
                category:this.bForm.category,
                tag:this.bForm.tag,
                new_tag:this.bForm.new_tag,
            }
            let url = `/api/admin/blog`
            if(id && id !== 0){
                url = `/api/admin/blog/${id}`
                axios.put(url,fData)
                    .then(res=>{
                        this.res_status = res.data.msg

                        // remove the cookies after success updated
                        if(this.$cookies.get("editId") && 
                            this.$cookies.get("editId") !== 0){
                            this.$cookies.remove("editId")
                        }
                        setTimeout(()=>{
                            this.res_status = ''
                            this.bForm.reset()
                           this.$emit('getBlog') 
                        },2500)
                    })
                    .catch(err=>{
                        this.res_status = `<span class="
                        tag is-medium has-text-weight-bold has-text-danger
                        ">
                    ${Object.values(err.response.data.errors).join()}
                            </span>`
                    })
            }else{
                axios.post(url,fData)
                    .then(res=>{
                        this.res_status = res.data.msg
                        setTimeout(()=>{
                            this.res_status = ''
                            this.bForm.reset()
                           this.$emit('getBlog') 
                        },2500)
                    })
                    .catch(err=>{
                        this.res_status = `<span class="
                        tag is-medium has-text-weight-bold has-text-danger
                        ">
                    ${Object.values(err.response.data.errors).join()}
                            </span>`
                    })
            }
            }


        },
        formHasError(){

            if(!this.bForm.category || 
                this.bForm.category === '' || this.bForm.bl_title === ''){
                alert('Error please check your input')
                return true
            }else{
                return false
            }
        },
        setCategory(){
            // set the category id
            this.bForm.category = this.$refs.category.value
            //console.log(`the value of bl cat is ${this.bForm.category}`)
        },
        saveTag(){
            // create new tag 
            if(this.bForm.new_tag.length >= 4){
                let url = `/api/admin/tag`
                let tData = {tag_name:this.bForm.new_tag}
                axios.post(url,tData)
                    .then(res=>{
                        this.res_status = res.data.msg
                        setTimeout(()=>{
                            this.res_status = ''
                            this.$emit('getBlog')
                            this.bForm.new_tag = ''
                        },2500)
                    })
                    .catch(err=>{
                        this.res_status = `<span class="
                        tag is-medium has-text-danger has-text-weight-bold
                        ">
                        ${Object.values(err.response.data.errors).join()}
                    </span>`
                    })
            }else{
                alert('more letters please')
                return
            }
        },
        copyExcerptCode(){
            this.$refs.bl_excerpt.select()
            document.execCommand('copy')
        },
        copyBodyCode(){
            this.$refs.bl_body.select()
            document.execCommand('copy')
        },
        closeAndRefresh(){
            this.bForm.reset()
            this.$emit("closeAndRefresh")
        },

    },
}
</script>
