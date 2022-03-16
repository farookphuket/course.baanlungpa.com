<template>
    <div>
        <!-- content list START -->
        <div class="box" v-if="!whatup.data || whatup.data == ''">
            <p class="title has-text-danger">
                there is no data!
            </p>
        </div>
        <div class="content" v-else>
                <article class="box " v-for="wp in whatup.data" 
                    >
                    <p class="title">{{wp.wp_title}}</p>
                    <div v-html="wp.wp_body"></div>

                    <div class="mb-4 mt-4">
                        <div class="columns is-mobile">
                            <div class="column">
                                <span class="mr-2">
                                    <font-awesome-icon 
                                        icon="calendar-plus">
                                    </font-awesome-icon>
                                </span>
                                <span class="mr-2">
                                    {{moment(wp.created_at)
                                    .format("DD-MM-YY")}}
                                </span>
                                <span >
                                    ({{moment(wp.created_at).fromNow()}})</span>
                            </div>
                            <div class="column">
                                <div class="field is-pulled-left">
                                    <div>
                                        <span class="mr-2">
                                            <font-awesome-icon 
                                                icon="user"></font-awesome-icon>
                                        </span>
                                        <span >{{wp.user.name}}</span>
                                    </div>
                                    <div>
                                        <span class="tag is-medium is-danger is-light 
                                        has-text-info  has-text-weight-bold" 
                                            v-if="wp.is_public === 0">Private</span>
                                    </div>

                                </div>
                                <div class="field is-pulled-right">
                                    <button class="button is-primary 
                                         is-small is-rounded is-outlined" 
                                        @click="$emit('edit',wp.id)">
                                        <font-awesome-icon icon="edit">
                                        </font-awesome-icon>
                                    </button>

                                    <button class="button is-danger 
                                          is-small is-rounded ml-2 is-outlined" 
                                        @click="$emit('del',wp.id)">
                                        <font-awesome-icon icon="trash">
                                        </font-awesome-icon>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>



                    <div class="content mb-6 mt-4">
                        <div class="field is-pulled-right" 
                            >

                        </div>
                    </div>
                </article>

        </div>
        <!-- content list end -->
        <!-- pagination area start -->
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
        <!-- pagination area end -->
    </div>
</template>
<script>
var moment = require('moment')
export default{
    name:"WhatupList",
    props:["whatup"],
    data(){
        return{
            moment:moment,
            today:'',
            user_id:'',
        }
    },
    mounted(){
        this.today = new Date()
        this.user_id = window.lsDefault.user_id
    },

}
</script>
