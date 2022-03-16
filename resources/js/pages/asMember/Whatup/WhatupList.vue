<template>
    <div class="container">
        <div class="content" v-if="whatup.data == ''">
            <p class="title has-text-centered">
                There is no data yet!
            </p>
        </div>
        <article class="box mb-4" v-for="wp in whatup.data"
            v-else>
            <p class="title">{{wp.wp_title}}</p>

            <div class="content mb-4">
                <div v-html="wp.wp_body"></div>
            </div>
            <!-- bottom status START -->
            <div class="mt-6 pb-4">
                <div class="columns is-mobile">
                    <div class="column">
                        <span class="mr-2">
                            <font-awesome-icon 
                                icon="user"></font-awesome-icon>
                        </span>
                        <span>{{wp.user.name}}</span>
                    </div>
                    <div class="column">
                        <span class="mr-2">
                            <font-awesome-icon 
                                icon="calendar-week">
                            </font-awesome-icon>
                        </span>
                        <span>{{moment(wp.created_at)
                            .format("DD-MMMM-YYYY")}}</span>
                        <span class="ml-2">{{moment(wp.created_at)
                            .fromNow()}}</span>
                    </div>

                    <div class="column pb-4" v-if="isOwner == wp.user_id">
                        <div class="field is-pulled-left">
                            <span class="tag has-text-success 
                                has-text-weight-bold is-medium" 
                                v-if="wp.is_public == 1">
                                Public
                            </span>
                            <span class="tag has-text-danger 
                                has-text-weight-bold is-medium" 
                                v-else>
                                Private
                            </span>
                        </div>
                        <div class="field is-pulled-right">
                            <button class="button is-primary is-small 
                                is-rounded is-outlined" 
                                @click.prevent="$emit('edit',wp.id)">
                                <font-awesome-icon 
                                    icon="edit"></font-awesome-icon>
                            </button>
                            <button class="button is-danger is-small 
                                is-rounded is-outlined ml-2" 
                                @click.prevent="$emit('del',wp.id)">
                                <font-awesome-icon 
                                    icon="trash"></font-awesome-icon>
                            </button>
                        </div>
                    </div>
                </div><!-- end of div.columns -->

            </div>
            <!-- bottom status START -->

        </article>

        <!-- pagination START -->
            <div class="d-flex justify-content-center mt-4 mb-4" v-if="whatup.total != 0">

                <nav class="pagination is-rounded" role="navigation" 
                    aria-label="pagination">
                    <a class="pagination-previous is-current">post {{whatup.from}} of
                        {{whatup.total}}</a>
                    <a class="pagination-next is-current">
                        page {{whatup.current_page}}/{{whatup.last_page}}</a>


                  <ul class="pagination-list" v-for="ln in whatup.links">
                    <li v-if="ln.url != null && ln.active == false">
                      <a class="pagination-link" 
                      aria-label="Page 1" aria-current="page" v-html="ln.label" 
                      @click.prevent="$emit('getWhatup',ln.url)"></a>
                    </li>
                    <li v-else>
                      <a class="pagination-link is-current"  v-if="ln.active == true" 
                      aria-label="" aria-current="page" v-html="ln.label" 
                      ></a>

                      <a class="pagination-link"  v-else 
                      aria-label="" aria-current="page" v-html="ln.label" 
                      ></a>
                    </li>

                  </ul>
                </nav>
            </div>
        <!-- pagination END -->

    </div>
</template>
<script>
var moment = require("moment")
export default{
    name:"WhatupList",
    props:["whatup"],
    data(){return{
        moment:moment,
        isOwner:0,
    }},
    mounted(){
        this.isOwner = window.lsDefault.user_id
    },

}
</script>
