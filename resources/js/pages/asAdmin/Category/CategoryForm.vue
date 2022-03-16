<template>
    <div>
        <div class="mb-4 pb-4">
            <form action="">
                <div class="field">
                    <div class="control">
                        <input v-model="cForm.cat_name" class="input" 
                        type="text" name="" 
                        placeholder="Category name..." 
                        ref="cat_name" 
                        >
                    </div>
                    <div class="mt-2 mb-3" v-if="cForm.cat_name.length !== 0">
                        <p>{{cForm.cat_name}} ({{cForm.cat_name.length}}) 
                            <span class="tag is-medium has-text-weight-bold 
                                has-text-danger" 
                                  v-if="cForm.cat_name.length >= 21"
                                >Error:content too long! 
                                4-20 letters please.</span>
                        </p>
                        <input type="hidden" name="" 
                        v-model="cForm.cat_slug">
                        <p>{{theSlug.thaiSlug(cForm.cat_name)}}</p>
                    </div>
                </div>
               <div class="mt-2 mb-4">

                   <div class="columns is-mobile">
                       <div class="column">
                           <div class="field is-pulled-left">
                               <div v-html="res_status"></div>
                           </div>
                       </div>
                       <div class="column">
                           <div class="field  pr-2">
                               <label class="checkbox" for="">
                                   <input v-model="cForm.is_allow_edit" 
                                   type="checkbox" 
                                   name="">
                                   <span class="ml-2">Allow editing</span>
                               </label>
                           </div>
                       </div>
                       <div class="column">
                           <div class="field is-pulled-right mr-2 pr-2">
                               <button class="button is-small is-primary is-outlined 
                                   is-rounded" 
                                   @click.prevent="saveCategory(editId)">
                                   <font-awesome-icon 
                                       icon="check"></font-awesome-icon>
                               </button>
                           </div>
                       </div>
                   </div>
               </div>
               <!-- submit button START -->
               <!-- submit button END -->
            </form>
        </div>
    </div>
</template>
<script>
export default{
    name:"CategoryForm",
    props:["editId"],
    data(){return{
        theSlug:new CustomText(),
        cForm:new Form({
            cat_name:'',
            is_allow_edit:'',
            cat_slug:''
        }),
        res_status:'',
    }},
    watch:{
        "editId":function(x){
            this.getEditData(x)
        }
    },
    methods:{
        getEditData(x){
            if(x != 0){
                this.$refs.cat_name.focus()
                this.cForm.is_allow_edit = false
                let url = `/api/admin/category/${x}`
                axios.get(url)
                    .then(res=>{
                        let rData = res.data.category
                        this.cForm.cat_name = rData.cat_name
                        if(rData.is_allow_edit !== 0) this.cForm.is_allow_edit = true
                    })
            }
        },
        saveCategory(id){
            if(this.cForm.cat_name.length > 4){
                let url = `/api/admin/category`
                let fData = new FormData()
                fData.append("cat_name",this.cForm.cat_name)
                fData.append("is_allow_edit",this.cForm.is_allow_edit)
                fData.append("cat_slug",this.theSlug.thaiSlug(this.cForm.cat_name))

                if(id && id !== 0){
                    url = `/api/admin/category/${id}`
                    fData.append("_method","PUT")
                }
                axios.post(url,fData)
                    .then(res=>{
                        this.res_status = res.data.msg
                        setTimeout(()=>{
                            this.$emit('getCategory')
                            this.res_status = ''
                            this.cForm.reset()
                        },2500)
                    })
                    .catch(err=>{
                        this.res_status = `<span class="tag is-medium 
                        has-text-danger has-text-weight-bold">
                            ${Object.values(err.response.data.errors).join()}
                        </span>`
                    })
            }else{
                alert('More letters please')
                return
            }
        },
    },
}
</script>
