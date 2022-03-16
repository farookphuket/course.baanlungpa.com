<template>
    <div>
        <div class="mt-4 mb-4 pb-4">
            <p class="title">
                Static page {{isNoStaticPage}} item(s).
            </p>
            <div class="box">
                <p class="title" 
                    v-if="isNoStaticPage === 0">there no data</p>
                <article 
                    v-for="p in static_page.data"
                    v-else>
                    <p class="title">
                        <span class="mr-2">
                            <a href="" 
                                @click.prevent="openStatic(p.d_slug)">
                                {{p.d_title}}
                            </a>
                            </span>
                        <span class="ml-2 mr-2 tag is-medium 
                            has-background-white has-text-danger" 
                            v-if="p.d_is_public === 0">
                            <font-awesome-icon 
                                icon="eye"></font-awesome-icon> 
                            <span class="ml-3">Not show</span>
                        </span>
                        <span class="ml-2 mr-2 tag  is-medium 
                            has-background-white has-text-success" 
                            v-else>
                            <font-awesome-icon 
                                icon="eye"></font-awesome-icon>
                            <span class="ml-2">Show</span>
                        </span>
                        
                    </p>
                    <p class="subtitle">
                        <span class="mr-2">
                            <font-awesome-icon 
                                icon="user"></font-awesome-icon>
                        </span>
                        <span class="ml-2 mr-4">
                            {{p.user.name}}
                        </span>
                        <span>
                            {{p.d_slug}}
                        </span>
                    </p>

                    <div class="mt-2 mb-4 pb-4">
                        <div class="columns is-mobile">
                            <div class="column">
                                <div class="field">
                                    <p>
                                        <span class="mr-2">
                                            <font-awesome-icon 
                                                icon="calendar-week"></font-awesome-icon>
                                        </span>
                                        <span class="mr-4">
                                            {{moment(p.created_at)
                                            .format("YYYY-MMM-DD HH:mm:s")}}</span>
                                        <span>
                                        {{moment(p.created_at)
                                            .fromNow()}}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="column">
                                <div class="mb-4">
                                    <div class="field is-pulled-right">
                                        <button class="button is-primary 
                                        is-small is-rounded is-outlined" 
                                            @click.prevent="$emit('edit',p.id)">
                                            <font-awesome-icon 
                                                icon="edit"></font-awesome-icon>
                                        </button>

                                        <button class="button is-danger 
                                        is-small is-rounded is-outlined" 
                                            @click.prevent="$emit('del',p.id)">
                                            <font-awesome-icon 
                                                icon="times"></font-awesome-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </article>
            </div>
        </div>

    </div>
</template>
<script>

var moment = require("moment")
export default{
    name:"StaticPageList",
    props:["static_page","isNoStaticPage"],
    data(){return{
        moment:moment,
    }},
    methods:{
        openStatic(slug){
            //alert(`will open ${slug}`)
            let url = `/static-site/${slug}`
            location.href=url
        },
    },
}
</script>
