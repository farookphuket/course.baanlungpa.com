<template>
    <div>
                <h1 class="title has-text-centered">
                    currently working on
                </h1>
                <p class="subtitle">
                    baanlungpa.com project which using 
                </p>
                <div class="content">
                    <p class="mb-4">
                        I using all this free code to create my web app and 
                        I will share this to you if you want to.
                    </p>
                    <ol class="is-lower-roman">
                        <li>
                            <span class="tag is-medium is-success">
                                Laravel 8 as core code
                            </span>
                        </li>

                        <li>
                            <span class="tag is-medium is-success">
                                agent from https://github.com/jenssegers/agent
                            </span>
                        </li>

                        <li>
                            <span class="tag is-medium is-success">
                                BulmaCss for the styling.
                            </span>
                        </li>
                        <li>
                            <span class="tag is-medium is-success">
                                Vue 3 and Vue-router as front-end
                            </span>
                        </li>

                        <li>
                            <span class="tag is-medium is-success">
                                font awesome for some cool font 
                            </span>
                            <span class="ml-3">
                                <button class="button is-outlined is-rounded 
                                    is-small is-success">
                                    <font-awesome-icon icon="check"></font-awesome-icon>
                                </button>
                            </span>
                        </li>
                        <li>
                            <span class="tag is-medium is-success">
                                momentJs to format date (ago time)
                            </span>
                        </li>
                        <li>
                            <span class="tag is-medium is-success">
                                Vue3-cookies for the cookie 
                            </span>
                        </li>
                        <li>
                            <span class="tag is-medium is-warning">
                                Jodit-vue3 for WYSIWYG html editor 
                            </span>
                        </li>
                    </ol>
                </div>
                <p>
                    this web has created on 21 Jan 2003 or 
                    {{moment("2003-01-21 23:45:50")
                    .format("YYYY-MMMM-DD H:m:s a")}} or
                    {{moment("2003-01-21 23:45:50").fromNow()}}
                </p>
    </div>
</template>
<script>
var moment = require('moment')
export default{
    name:"AboutBannLungPa",
    data(){return{
        moment:moment,
    }},
}
</script>
