<template>
    <div>
        <div class="mt-4 mb-4 pb-6">
            <div class="box" 
                v-if="isNoClassRoom === true">
                <p class="title has-text-danger has-text-centered">
                    There is No ClassRoom!, create one first!
                </p>
            </div>


            <form action="" enctype="multipart/form-data">
                
                <!-- room,course year ,course term START -->
                <div class="columns is-mobile">
                    <div class="column">

                        <div class="field">
                            <label class="label" for="">
                                ClassRoom (ห้องเรียน)
                            </label>
                            <div class="select is-fullwidth">
                                <select v-model="cForm.classroom" name="">
                                    <option disabled value=""> -- Select --</option>
                                    <option :value="c.id" 
                                            v-for="c in classrooms">{{c.class_title}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" for="">
                                Course Term (เทอม) :
                            </label>
                            <div class="select is-fullwidth">
                                <select v-model="cForm.course_term" name="">
                                    <option value="" disabled> -- Select --</option>
                                    <option value="1">Term 1</option>
                                    <option value="2">Term 2</option>
                                    <option value="3">Term 3</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label" for="">
                                Course Year (ปีที่ออก)
                            </label>
                            <div class="control">
                                <input v-model="cForm.course_year" 
                                type="number" name="" 
                                class="input">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" for="">
                                Credit (คะแนน)
                            </label>
                            <div class="control">
                                <input v-model="cForm.course_credit" 
                                class="input" type="number" name="" 
                                placeholder="Credit คะแนน">
                            </div>
                        </div>

                    </div><!-- end of div.column 2 -->

                    <div class="column">
                        <div class="field">
                            <label class="label" for="">
                                Course ID :
                            </label>
                            <div class="control">
                                <input v-model="cForm.course_id" 
                                class="input" type="number" 
                                ref="course_id" 
                                placeholder="Auto">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" for="">
                                Course Hours.(ชั่วโมงเรียน)
                            </label>
                            <div class="control">
                                <input v-model="cForm.course_hours" 
                                class="input" type="number" name="" 
                                placeholder="Enter Hours">
                            </div>
                        </div>

                    </div><!-- end of div.column 3 -->

                </div><!-- end of div.columns -->
                <!-- 
                    room,course year ,course term ,course credit,course id, 
                    END -->

                <!-- course is free ,is public START -->
                <div class="columns is-mobile">
                    <div class="column">
                        <div class="box">
                            <p class="title">is this a free course?</p>
                            <p class="mb-4">
                                if you have check this box the course will be 
                                show to everyone as it a free course.
                            </p>

                            <div class="field">
                                <div class="control">
                                    <label class="checkbox" for="">
                                        <input 
                                        v-model="cForm.course_is_free" 
                                        type="checkbox" name="">
                                        <span class="ml-2 has-text-success" 
                                            v-if="cForm.course_is_free === true">
                                            yes,it's free course</span>
                                        <span class="ml-2 has-text-danger" v-else>
                                            No,it's Proprietary Course
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="column">
                        <div class="box">
                            <p class="mb-4">
                                Do you want this to be public? 
                                your student will not be seeing this course 
                                unless you have allow them to see.
                            </p>
                            <p class="mt-2 mb-4">
                                ถ้าไม่ติ๊กเลือกช่องนี้ นักเรียนจะไม่เห็นคอร์สนี้
                            </p>
                            <div class="field">
                                <div class="control">
                                    <label class="checkbox" for="">
                                        <input 
                                        v-model="cForm.course_is_public" 
                                        type="checkbox" name="">
                                        <span class="ml-2 has-text-weight-bold 
                                        has-text-success" 
                                            v-if="cForm.course_is_public !== false">
                                            is public</span>
                                        <span class="ml-2 has-text-warning 
                                              is-uppercase" 
                                            v-else>is NOT public</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- course is free ,is public END -->



                <div class="field">
                    <label class="label" for="">
                        Course Name (ชื่อวิชา)
                    </label>
                    <div class="control">
                        <input v-model="cForm.course_name" class="input" 
                        type="text" name="" 
                        placeholder="Enter course title">
                    </div>
                </div>

                <div class="mt-4 mb-4">
                    <p class="title">
                        add cover for this course?
                    </p>


                    <!-- div.columns START -->
                    <div class="columns is-mobile">
                        <div class="column">

                            <p class="mb-2">
                                select the image from your computer
                            </p>

                            <div class="file has-name">
                              <label class="file-label">
                                <input class="file-input" type="file" 
                                ref="course_cover_file" 
                                @change="showImagePreview"
                                >
                                <span class="file-cta">
                                  <span class="file-icon">
                                      <font-awesome-icon 
                                          icon="upload"></font-awesome-icon>
                                  </span>
                                  <span class="file-label">
                                    Choose a file…
                                  </span>
                                </span>
                                <span class="file-name">
                                    {{preview_text}}
                                </span>
                              </label>
                            </div>

                        </div>
                        <div class="column">
                            <p class="mb-2">
                                paste the image url then hit enter
                            </p>
                            <div class="field has-addons">

                                <div class="control">
                                    <input v-model="cForm.course_cover_url" 
                                    class="input" 
                                    ref="course_cover_url"
                                    type="text" name="" 
                                    placeholder=
            "paste image url then hit the eye icon..." 
                                    :disabled="image_input_disable" 
                                    >

                                </div>

                                <div class="control">
                                    <a class="button is-link" href="" 
                                        @click.prevent="showUrlImagePreview">
                                        <font-awesome-icon 
                                            icon="eye"></font-awesome-icon>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- div.columns END -->

                    <!-- show image cover START -->
                    <div class="mb-4">
                        <div class="field has-text-centered">
                            <figure class="justify-content-center">
                                <img :src="preview_image" :alt="preview_text">
                            </figure>
                            <p>{{preview_text}}</p>

                            <p v-if="upload_file_is_too_big === true">
                                <span class="has-text-danger 
                                    has-text-weight-bold">

                                    {{upload_cover.size}} MB.
                                </span> 
                                <span class="has-text-danger">
                                    Error! your file is too big! 
                                    please make sure your file is not bigger 
                                    than 2 MB. in size.
                                </span>

                            </p>
                            <p v-else>
                                <span class="has-text-info 
                                    has-text-weight-bold">
                                    {{upload_cover.size}} MB.
                                </span>
                            </p>
                        </div>

                    </div>
                    <!-- show image cover START -->

                </div>

                <!-- course excerpt START -->
                <div class="mb-4 pb-4">
                    <div class="field">
                        <label class="label" for="">
                            Course excerpt
                        </label>
                        <div class="control">
                            <textarea v-model="cForm.course_excerpt" 
                                      class="textarea" 
                                ></textarea>
                        </div>
                    </div>
                </div>

                <!-- course excerpt END -->


                <!-- course body START -->
                <div class="field">
                    <div class="control">
                        <jodit-editor 
                            height=450 
                            :editorOptions="options"
                            v-model="cForm.course_body" 
                            ></jodit-editor>
                    </div>

                    <div class="mt-4" 
                        v-if="cForm.course_body.length >= 20">
                        <div class="mb-6 pb-4">
                            <div class="field is-pulled-right">
                                <button class="button is-primary is-rounded 
                                    is-outlined">
                                    <font-awesome-icon 
                                        icon="copy"></font-awesome-icon>
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <textarea v-model="cForm.course_body" 
                                    class="textarea" name="" 
                                    ></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- course body END -->
                <div class="columns is-mobile">
                    <div class="column">
                        <div v-html="res_status"></div>
                    </div>
                    <div class="column">
                        <div class="field is-pulled-right">
                            <button class="button is-primary is-rounded 
                                is-outlined" 
                                type="submit" 
                                @click.prevent="saveCourse(editId)">
                                <font-awesome-icon 
                                    icon="check"></font-awesome-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>

    </div>
</template>
<script>


export default{
    name:"CourseForm",

    props:["editId","classrooms","isNoClassRoom","autoCourse_id"],
    data(){
        return{
            cForm:new Form({
                classroom:'',
                course_year:'',
                course_cover_file:'',
                course_cover_url:'',
                course_term:'',
                course_id:'',
                course_credit:'',
                course_name:'',
                course_excerpt:'',
                course_body:'',
                course_hours:'',
                course_is_free:'',
                course_is_public:false
            }),
            res_status:'',
            cur_year:new Date().getFullYear()+543,
            isNewCourse:true,
            options:{
                disablePlugins:"powered-by-jodit",
            },
            image_input_disable:false,
            preview_image:'',
            preview_text:'',
            upload_file_is_too_big:false,
            upload_cover:{
                file:false,
                img:null,
                size:0,
            },

        }
    },
    watch:{
        "editId":function(x){
            this.getEditData(x)
        },
        "autoCourse_id":function(x){
            this.getAutoCourseId(x)
        }
    },
    mounted(){

        this.getFormStart()


    },
    methods:{
        getEditData(x){
            alert(this.editId)
        },
        getAutoCourseId(x){
            this.cForm.course_id = x
        },
        getFormStart(){

            this.cForm.course_year = this.cur_year-1

        },
        saveCourse(id){
            let url = '/api/teacher/course'
            let c_id = this.autoCourse_id
            if(this.cForm.course_id === 0 
                || this.cForm.course_id === ''){
                this.cForm.course_id = c_id
                 }
            let fData = new FormData()
            fData.append('classroom',this.cForm.classroom)
            fData.append('course_name',this.cForm.course_name)

            // the image cover file upload
            fData.append('course_cover_file',this.cForm.course_cover_file)


            // the image cover url
            fData.append('course_cover_url',this.cForm.course_cover_url)

            fData.append('course_excerpt',this.cForm.course_excerpt)
            fData.append('course_body',this.cForm.course_body)
            fData.append('course_year',this.cForm.course_year)
            fData.append('course_term',this.cForm.course_term)
            fData.append('course_id',this.cForm.course_id)
            fData.append('course_credit',this.cForm.course_credit)
            fData.append('course_hours',this.cForm.course_hours)
            fData.append('course_is_free',this.cForm.course_is_free)
            fData.append('course_is_public',this.cForm.course_is_public)

            if(id && id !== 0){
                url = `/api/teacher/course/${id}`
                fData.append("_method","PUT")
            }
            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                })
                .catch(err=>{
                    this.res_status = `
                    <span class="has-text-weight-bold has-text-danger">
                        ${Object.values(err.response.data.errors).join()}
                    </span>
                    `
                })
        },
        showImagePreview(e){

            /*
this will clear the previous value of the image url if the user has click 
select new upload file
                */
            this.upload_file_is_too_big = false
            this.$refs.course_cover_url.value = ''
            this.cForm.course_cover_url = ''

            const theFile = e.target.files[0]
            let file_size = theFile.size/1024/1024
            if(file_size > 2){
                this.upload_file_is_too_big = true

            }

            this.upload_cover.file = theFile
            this.upload_cover.img = URL.createObjectURL(theFile)
            this.upload_cover.size = file_size
            
            this.preview_image = this.upload_cover.img 
            this.preview_text = this.upload_cover.file.name

            // set the form 
            this.cForm.course_cover_file = theFile
        },
        showUrlImagePreview(){

            /*
this will clear the previous value of the image upload if the user has click 
select new upload file
                */
            

            const theUrl = this.$refs.course_cover_url.value

            if(theUrl && theUrl !== ''){
                this.upload_cover = {}
                this.$refs.course_cover_file.value = ''


                this.preview_image = theUrl 
                this.preview_text = theUrl

                // then set the image url to form value
                this.cForm.course_cover_url = theUrl
            }else{
                alert(`Error! Empty url or invalid url has given!`)
                return
            }

            //console.log(theUrl)
        },
    },
}
</script>
