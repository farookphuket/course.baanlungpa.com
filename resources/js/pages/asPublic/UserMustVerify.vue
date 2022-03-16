<template>
    <section class="body_content">

        <div class="container">
            <p class="title has-text-centered has-text-info p-2">
                please confirm your email.
            </p>

            <p class="box mb-4">
                you're not confirm your email yet! please check your email 
                and click the confirmation link or copy code then paste 
                to the confirm box below then hit enter.
            </p>
            <div class="box mt-4 mb-4 pt-2 pb-2">

                <form class="mt-2" action="">
                    <div class="field">
                        <div class="control">
                            <textarea v-model="uForm.token" ref="key"
                                @keydown.enter.prevent="sentVerify"
                                class="textarea" ></textarea>
                        </div>
                    </div>
                    <div class="mt-2  pt-4 mb-6">
                        <div class="field is-pulled-right">
                            <button class="button is-block is-outlined 
                                is-primary">
                                <font-awesome-icon 
                                    icon="check"></font-awesome-icon>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
export default{
    name:"UserMustVerify",
    data(){return{
        token:'',
        uForm:new Form({
            token:''
        }),
    }},
    mounted(){
        setTimeout(()=>{
            this.$refs.key.focus()
        },2500)
    },
    methods:{
        sentVerify(){
            this.token = this.uForm.token
            let url = `/userHasConfirmed/${this.token}`
            setTimeout(()=>{
                location.href=url
            },800)
        },
    },

}
</script>
