<template>
    <section class="body_content">
        <div class="container">
            <div class="box" v-if="useDefault === true">
                <about></about>
            </div>
            <article class="box" 
                v-else>

                <p class="title">{{pageToLoad.d_title}}</p>
                <div class="content mt-4 mb-4 pb-4">
                    <div v-html="pageToLoad.d_body"></div>
                </div>
            </article>

        </div>
    </section>
</template>
<script>
import About from "./About.vue"
export default{
    name:"StaticPage",
    components:{
        About,
    },
    data(){
        return{
            theSlug:'',
            pageToLoad:'',
            useDefault:false,
        }
    },
    mounted(){
        this.getWhatSlug()
    },
    methods:{
        getWhatSlug(){
            let slug = this.$route.params.slug
            this.theSlug = slug
            this.getPageLoad(slug)
        },
        getPageLoad(slug){
            let url = `/api/static-site/${slug}`
            axios.get(url)
                .then(res=>{
                    this.pageToLoad = res.data.page
                    document.title = this.pageToLoad.d_title
                    //console.log(this.pageToLoad)
                    this.useDefault = false
                })
                .catch(err=>{
                    //console.log(err.response.status)
                    if(err.response.status === 404){
                        // load page not found 
                       //location.href='/page-not-found' 
                        this.useDefault = true
                    }
                })
        },
    },
}
</script>
