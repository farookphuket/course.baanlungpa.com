<template>
    <div>
        <div class="columns is-mobile">

            <div class="column">
                <about-baan-lung-pa></about-baan-lung-pa>
            </div>
            <div class="column">
                <div class="mb-4">
                    <p class="title">
                        JoditEditor is my problem 
                    </p>
                    <p class="subtitle">
                        when I change from "vue2" to "vue3" 
                    </p>
                    <p>
                        I cannot set the value for jodit editor when come to 
                        the edit.
                    </p>
                    <p>
                        the only solution that I can do so far is to just 
                        create the textarea then copy the previous data from 
                        the edit id that has being sent back.
                    </p>
                    <p>
                        type something in the text box now
                    </p>
                </div>
                <div class="field">

                    <form action="">

                        <div class="field">
                            <div class="control has-icons-right">
                                <input v-model="tForm.theTitle" class="input" 
                                type="text" placeholder="Enter your title...">
                                <span class="icon is-right is-small" 
                                    v-if="tForm.theTitle.length !== 0">
                                    {{tForm.theTitle.length}}
                                </span>
                            </div>
                            <p class="pt-2 pb-2" 
                                v-if="tForm.theTitle.length !== 0">
                                {{theSlug.thaiSlug(tForm.theTitle)}}
                            </p>
                        </div>

                        <!-- jodit textarea START -->
                        <div class="field">
                            <div class="control">
                                <jodit-editor 
                                    height=450 
                                    ref="msgBody"
                                    v-model="tForm.theBody"></jodit-editor>
                            </div>
                            <div class="pt-4" 
                                 v-if="tForm.theBody.length >= 10">
                                 <div class="mb-4 mt-2 pb-4">
                                     <div class="field is-pulled-right">
                                         <button class="button is-primary 
                                             is-small is-rounded 
                                             is-outlined" 
                                             @click.prevent="copyMe">
                                             <font-awesome-icon 
                                                 icon="copy"></font-awesome-icon>
                                         </button>
                                     </div>
                                 </div>
                                <div class="field">
                                    <div class="control has-icons-right">
                                        <textarea v-model="tForm.theBody" 
                                            class="textarea" 
                                            ref="theBody"></textarea>
                                        <span class="icon is-right">
                                            {{tForm.theBody.length}}
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- jodit textarea END -->

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

var moment = require('moment')
import {JoditEditor} from 'jodit-vue3'
import AboutBaanLungPa from "./AboutBaanLungPa.vue"
export default {
    name: "About",
    components:{
        AboutBaanLungPa,
    },
    data(){
        return{
            moment:moment,
            theSlug:new CustomText(),
            tForm:new Form({
                theTitle:'',
                theBody:'',
            }),
        }
    },
    mounted(){
        this.getWhatup()
    },
    methods:{
        getWhatup(){

            this.tForm.theBody = `
<p class="title">I am a text from server</p>
            <p>I can promise that i will not show in the 
            jodit editor on the first load</p>
<pre>
    <code><span>
type some code to show in here 
    </span></code>
</pre>
                `


        },
        copyMe(){
            this.$refs.theBody.select()
            document.execCommand('copy')
        },
    },
}
</script>

