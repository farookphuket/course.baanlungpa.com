<template>
    <div >
            <p class="title">
                Create/Edit ClassRoom
            </p>

            <form action="">
                <div class="field">
                    <div class="control has-icons-right">
                        <input v-model="cForm.class_title" class="input" 
                        type="text" name="" 
                        placeholder="Enter Class Title">
                        <span class="icon is-right">
                            {{cForm.class_title.length}}</span>
                    </div>
                </div>

                <div class="field pb-4">

                    <label for="" class="label">Description</label>
                    <div class="control has-icons-right">
                        <textarea v-model="cForm.class_description" 
                            class="textarea" name="" 
                            placeholder="classroom Decsription "></textarea>
                        <span class="icon is-right">
                            {{cForm.class_description.length}}</span>
                    </div>
                </div>

                <div class="columns is-mobile">
                    <div class="column">
                        <div class="field">
                            <label for="" class="label">
                                Level ระดับชั้น : 
                                <span>{{cForm.class_level}}</span>
                            </label>
                            <select v-model="cForm.class_level" name="" class="select">
                                <option disabled value="">-- seclect --</option>
                                <option value="1">year 1</option>
                                <option value="2">year 2</option>
                                <option value="3">year 3</option>
                                <option value="4">year 4</option>
                                <option value="5">year 5</option>
                                <option value="6">year 6</option>
                                <option value="7">year 7</option>
                            </select>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label" for="">
                                Year (ปี ) : 
                                <span>{{cForm.class_year}}</span>
                            </label>
                            <div class="control">
                                <input v-model="cForm.class_year" 
                                class="input" type="number" name="" 
                                placeholder="Enter year ">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6 pb-4">
                    <div class="columns is-mobile">
                        <div class="column">
                            <div v-html="res_status"></div>
                        </div>
                        <div class="column">
                            <div class="field is-pulled-right">
                                <button class="button is-primary is-outlined 
                                    is-rounded" type="submit" 
                                    @click.prevent="saveClass(editId)">
                                    <font-awesome-icon 
                                        icon="check"></font-awesome-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>


    </div>
</template>
<script>
export default{
    name:"ClassForm",
    props:["editId"],
    data(){return{
        cForm:new Form({
            class_title:'',
            class_description:'',
            class_level:'1',
            class_year:'',
        }),
        res_status:'',
        curYear:new Date().getFullYear()
    }},
    mounted(){
        this.loadNewForm()
    },

    methods:{
        saveClass(id){
            let url = `/api/teacher/classroom` 
            let fData = new FormData()

            fData.append("class_title",this.cForm.class_title)
            fData.append("class_description",this.cForm.class_description)
            fData.append("class_level",this.cForm.class_level)
            fData.append("class_year",this.cForm.class_year)

            if(id && id !== 0){
                fData.append("_method","PUT")
                url = `/api/teacher/classroom/${id}`
            }
            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                    this.$emit('showBox',{msg:this.res_status,timeout:2500})
                    setTimeout(()=>{
                        this.res_status = ''
                        this.loadNewForm()
                        this.$emit("getClassRoom")
                    },2000)
                })
                .catch(err=>{
                    this.res_status = `<span class="is-medium 
                    has-text-danger has-text-weight-bold">
            ${Object.values(err.response.data.errors).join()}
                        </span>`
                })
        },
        loadNewForm(){
            this.cForm.reset()
            this.cForm.class_year = this.curYear+543
            this.cForm.class_level = 1
        },
    },
}
</script>
