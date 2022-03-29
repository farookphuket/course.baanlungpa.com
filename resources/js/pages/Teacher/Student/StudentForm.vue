<template>
    <div>
        <form action="">
            <div class="field">
                <div class="select">
                    <select v-model="sForm.class_id" name="">
                        <option value="">-- Select classroom --</option>
                        <option :value="cl.id" 
                        v-for="cl in classroom">
                        {{cl.class_title}}
                        </option>
                    </select>
                </div>

                <!-- start and finish -->
                <div class="columns is-mobile">
                    <div class="column">
                        <div class="box">
                            <p class="title">
                                Start on year
                            </p>
                        </div>
                        <div class="field">
                            <div class="control">
                                <input 
                                v-model="sForm.studied_at" class="input" 
                                type="number" name="">
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <div class="box">
                            <p class="title">
                                finish on year
                            </p>
                        </div>
                        <div class="field">
                            <div class="control">
                                <input 
                                v-model="sForm.successed_at" class="input" 
                                type="number" name="">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- start and finish -->
                <!-- student list start -->
                <div class="mt-4 mb-4">
                    <div class="columns is-mobile is-multiline">
                        <div class="column" 
                            v-for="st in student">
                            <label class="checkbox" for="">
                                <input v-model="set_student" 
                                type="checkbox" name="" 
                                :value="st.id" 
                                @change.prevent="setStudentToClass"
                                >
                                <span class="mr-2">
                                    {{st.name}}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- student list start -->

                <div class="columns is-mobile">
                    <div class="column">
                        <div v-html="res_status"></div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <button class="button is-primary is-rounded 
                                is-outlined" 
                                @click.prevent="saveStudent(editId)">
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
    name:"StudentForm",
    props:["classroom","student","editId"],
    data(){
        return{
            set_student:[],
            res_status:'',
            sForm:new Form({
                class_id:'',
                student_ids:[],
                studied_at:'',
                successed_at:''
            }),
        }
    },
    mounted(){
        this.reForm()
    },
    methods:{
        reForm(){
            this.sForm.reset()
            this.sForm.studied_at = new Date().getFullYear()+543
            this.sForm.successed_at = new Date().getFullYear()+543+4
        },
        setStudentToClass(){
            //console.log(this.set_student)
            this.sForm.student_ids = this.set_student
        },
        saveStudent(id){
            let url = `/api/teacher/student`

            let fData = new FormData()
            fData.append('classroom_id',this.sForm.class_id)
            fData.append('students',this.sForm.student_ids)
            fData.append('studied_at',this.sForm.studied_at)
            fData.append('successed_at',this.sForm.successed_at)

            if(id && id !== 0){
                fData.append("_method","PUT")
                url = `/api/teacher/student/${id}`
            }
            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                })
                .catch(err=>{
                    this.res_status = `<span class="has-text-danger 
                    has-text-weight-bold">
            ${Object.values(err.response.data.errors).join()}
                        </span>`
                })
        },
    },
}
</script>
