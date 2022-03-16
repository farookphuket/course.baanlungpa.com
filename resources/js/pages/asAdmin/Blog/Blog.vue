<template>
    <section class="body_content">

        <div class="container">

            <div class="mb-4 pb-6">
                <div class="field is-pulled-right">
                    <button class="button is-primary is-rounded is-outlined 
                        mr-2 " 
                        @click.prevent="showComment">
                        comment List
                    </button>

                    <button class="button is-primary is-rounded 
                       is-outlined" 
                        @click.prevent="isFormShow = true" 
                        v-if="isFormShow == false">
                        <span class="mr-2">
                            <font-awesome-icon 
                                icon="plus"></font-awesome-icon>
                        </span>
                        <span class="is-uppercase">
                            create post
                        </span>
                    </button>
                    <button class="button is-rounded is-danger 
                        is-outlined" 
                            @click.prevent="closeAndRefresh"
                        v-else>

                        <span class="mr-2">
                            <font-awesome-icon 
                                icon="times"></font-awesome-icon>
                        </span>
                        <span class="is-uppercase">
                            close
                        </span>
                    </button>
                </div>
            </div>

            <div class="mb-4" 
                >

                <blog-form :editId="editId" 
                    @getBlog="getBlog($event)" 
                    :tag="tag" :category="category" 
                    @closeAndRefresh="closeAndRefresh($event)" 
                    v-show="isFormShow"></blog-form>
            </div>

            <blog-list :blog="blog" 
                :tag="tag" 
                :tag_has_blog="tag_has_blog" 
                :cat_has_blog="cat_has_blog"
                :category="category" 
                @openBlog="openBlog($event)"
                @edit="edit($event)" 
                @del="del($event)" 
                @getBlog="getBlog($event)" 
                @showByCategoryId="showByCategoryId($event)" 
               v-show="isBlogList" ></blog-list>

            <category-list :category_id="category_id"  
                @getBlog="getBlog($event)"
                v-if="isBlogList !== true"></category-list>

        </div>
        <x-box ref="xbox" :msg="res_status"></x-box>
    </section>
</template>
<script>
import BlogForm from './BlogForm.vue'
import BlogList from "./BlogList.vue"
import xBox from "../../_include/BoxModal.vue"
import CategoryList from './CategoryList.vue'
export default{
    name:"AdminBlog",
    components:{
        BlogForm,
        BlogList,
        xBox,
        CategoryList,
    },
    data(){
        return{
            blog:"",
            tag_has_blog:[],
            cat_has_blog:[],
            isFormShow:false,
            tag:'',// all tag show in form
            category:'',// all category show in form
            editId:0,
            res_status:'',
            perpage:10,
            category_id:'',
            isBlogList:true,
        }
    },
    mounted(){
        this.getBlog()
    },
    methods:{
        getBlog(page){
            if(this.$cookies.get("editId") 
                && this.$cookies.get("editId") !== 0){
                this.editId = this.$cookies.get("editId")
            }else{
                this.editId = 0
            }
            this.tag_has_blog = [] 
            this.isBlogList = true
            let url = ''
            if(page){
                url = `${page}&perpage=${this.perpage}`
                this.$cookies.set("ab_old_page",url)
            }
            url = this.$cookies.get("ab_old_page")
            if(!url) url = `/api/admin/blog?perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{
                    this.blog = res.data.blog
                    this.tag = res.data.tag
                    this.category = res.data.category
                    //this.tag_has_blog = res.data.tag_has_blog
                    let the_tag = res.data.tag_has_blog
                    let the_cat = res.data.cat_has_blog
                    this.cat_has_blog = the_cat
                    this.tag_has_blog = the_tag


                })
        },
        openBlog(slug){
            let url = `/${slug}`
            location.href=url
        },
        edit(id){
            this.isFormShow = true
            this.editId = id
            this.$cookies.set("editId",this.editId)
        },
        del(id){
            if(id && id !== 0 && 
                confirm(`Delete blog id ${id}?`) === true){
                let url = `/api/admin/blog/${id}`
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        this.showBoxMsg(this.res_status,3200)
                        setTimeout(()=>{
                            this.getBlog()
                        },1200)
                    })
            }
            return

        },
        closeAndRefresh(){
            location.reload()
        },
        showBoxMsg(msg,myTime = 0){
            this.res_status = msg
            this.$refs.xbox.showBoxModal()

            if(myTime && myTime !== 0){
                setTimeout(()=>{
                    this.$refs.xbox.hideBoxModal()
                    this.res_status = ""
                },myTime)
            }

        },
        showByCategoryId(id){
            this.category_id = id
            this.isBlogList = false
          //  console.log(`from showByCategoryId the cat is ${this.category_id}`)
            let url = `/category/${id}`
            location.href=url
        },
        showComment(){
            let url = `/blog-comment`
            location.href=url
        },
    },

}
</script>
