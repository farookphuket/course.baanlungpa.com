<template>
    <div>
        <div class="mt-4 mb-4 pb-4">
            <div class="field is-pulled-right">
                <button class="button is-primary is-rounded is-link" 
                    @click.prevent="$emit('getClassRoom')">
                    Back
                </button>
            </div>
        </div>
        <p class="title">class room view {{classroom_id}}</p>
    </div>
</template>
<script>
export default{
    name:"ClassroomView",
    props:["classroom_id"],
    data(){
        return{
            c_room:'',
        }
    },
    mounted(){
        this.showClassroom(this.classroom_id)
    },
    methods:{
        showClassroom(x){
            let url = `/api/teacher/classroom/${x}`
            console.log(`the id is ${url}`)
            axios.get(url)
                .then(res=>{
                    let cRoomData = res.data.classroom
                    this.c_room = cRoomData
                    console.log(cRoomData)
                })
        },
    },
}
</script>
