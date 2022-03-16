<template>
    <div>
        <section class="body_content">
            <div class="container">

                <div class="mb-4 pb-6">
                    <div class="field is-pulled-right">
                       <button class="button is-primary" 
                           @click.prevent="backToBlog">
                           back
                       </button>
                    </div>
                </div>

                <article class="box mt-2 pb-4"
                    v-for="b1 in blog.data">
                    <p class="title">
                        <a href="" @click.prevent="openBlog(b1.bl_slug)">
                            {{b1.bl_title}}
                        </a>
                    </p>
                    <p class="subtitle">
                        {{b1.bl_slug}}
                    </p>
                </article>
                <!-- pagination START -->
                    <div class="mt-6 mb-4 d-flex justify-content-center" 
                        v-if="blog.data != ''">
                        <nav class="pagination is-rounded" role="navigation" aria-label="pagination">
                            <a class="pagination-previous is-current">All post(s) {{blog.total}}</a>
                            <a class="pagination-next is-current">page {{blog.current_page}}</a>
                          <ul class="pagination-list" v-for="ln in blog.links">
                            <li v-if="ln.url != null && ln.active == false">
                              <a class="pagination-link" 
                              aria-label="Page 1" aria-current="page" v-html="ln.label" 
                              @click.prevent="getByCategory(ln.url)"></a>
                            </li>
                            <li v-else>
                              <a class="pagination-link is-current"  v-if="ln.active == true" 
                              aria-label="" aria-current="page" v-html="ln.label" 
                              ></a>

                              <a class="pagination-link"  v-else 
                              aria-label="" aria-current="page" v-html="ln.label" 
                              ></a>
                            </li>

                          </ul>
                        </nav>
                    </div>
                <!-- pagination END -->
            </div>
        </section>
    </div>
</template>
<script>
export default{
    name:"BlogCategory",
    data(){return{
        blog:'',
        cat_id:'',
        perpage:5,
    }},
    created(){
        //console.log(this.$route.params.category_id)
        this.cat_id = this.$route.params.category_id
        this.getCategoryId()
    },

    methods:{
        getByCategory(page){
            let url = ''
            if(page){
                url = `${page}&cat_id=${this.cat_id}&
                    perpage=${this.perpage}`
                this.$cookies.set('aView_by_cat_old_page',url)

            }
            url = this.$cookies.get('aView_by_cat_old_page')
            if(!url) url = `/api/blog-by-category?cat_id=${this.cat_id}
                &perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{

                    this.$cookies.set("old_cat_id",this.cat_id)
                    this.blog = res.data.blog
                })
        },
        getCategoryId(){
            let old_id  = this.$cookies.get("old_cat_id")

            if(old_id !== this.cat_id){
                // user has visit the new category id 
                this.$cookies.remove("old_cat_id")
                this.$cookies.remove("aView_by_cat_old_page")
            }
            
            this.getByCategory()
        },
        openBlog(slug){
            let url = `/${slug}`
            location.href=url
        },
        backToBlog(){
            let url = `/blog`
            location.href=url
        },

    },
}
</script>
