<template>
    <div>
        <div class="box mt-4 mb-4">
            <form action="">
                <div class="field">
                    <div class="control">
                        <input v-model="tForm.tag_name" class="input" 
                            ref="tag_name" @keydown.enter.prevent="saveTag(editId)"
                        type="text" name="">
                    </div>
                    <div class="mt-2 mb-2" v-if="tForm.tag_name.length > 0">
                        <p>{{tForm.tag_name}} ({{tForm.tag_name.length}})
                            <span v-if="tForm.tag_name.length >= 21" 
                                class="tag is-medium has-text-danger 
                                has-text-weight-bold">
                                Error! Too long 4-20 letters please</span>
                        </p>
                    </div>
                </div>
                <div class="field mt-2 mb-2">
                    <div v-html="res_status"></div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>

export default{
    name:"TagForm",
    props:["editId"],
    data(){return{
        res_status:'',
        tForm:new Form({
            tag_name:'',
        }),
    }},
    watch:{
        "editId":function(x){
            this.getEditData(x)
        }

    },
    methods:{
        getEditData(id){
            if(id && id !== 0){
                this.$refs.tag_name.focus()
                let url = `/api/admin/tag/${id}`
                axios.get(url)
                    .then(res=>{
                    //    console.log(res.data)
                        this.tForm.tag_name = res.data.tag.tag_name
                    })
            }
        },
        saveTag(id){
            if(this.tForm.tag_name.length >= 4){
                let url = `/api/admin/tag`
                let fData = new FormData()
                fData.append("tag_name",this.tForm.tag_name)

                if(id && id !== 0){
                    url = `/api/admin/tag/${id}`
                    fData.append("_method","PUT")

                }

                axios.post(url,fData)
                    .then(res=>{
                        this.res_status = res.data.msg
                        setTimeout(()=>{
                            this.res_status = ''
                            this.tForm.reset()
                            this.$emit('getTag')
                        },2500)
                    })
                    .catch(err=>{
                        this.res_status = `<span class="tag is-medium 
                        has-text-weight-bold has-text-danger">
                            ${Object.values(err.response.data.errors).join()}
                        </span>`
                    })
            }else{
                alert(`More letters please!`)
                return
            }

        },
    },
}
</script>
