<template>
    <div>
        <div class="field box" v-if="blog.data == ''">
            <p class="title has-text-centered has-text-danger">
                Sorry! there is no data.
            </p>
        </div>
        <article class="box mb-2 mt-2 pb-4" 
        v-for="bl in blog.data"
            v-else>
            <p class="title">
                <a class="ml-2" href="" @click.prevent="$emit('openBlog',bl.bl_slug)">
                    {{bl.bl_title}}
                </a>
            </p>
            <!-- show slug,create date,user START -->
            <div class="mb-4 mt-2">
                <div class="columns is-mobile">
                    <div class="column">
                        <p class="subtitle">{{bl.bl_slug}}</p>
                    </div>
                    <div class="column">

                        <font-awesome-icon 
                            icon="calendar-week"></font-awesome-icon>
                        <span class="ml-2">
                            {{moment(bl.created_at).format("YYYY-MM-DD")}}
                        </span>
                        <span class="ml-2">
                            {{moment(bl.created_at).fromNow()}}
                        </span>
                    </div>
                    <div class="column">
                        <div class="mb-4">
                            <div class="is-pulled-left">
                                <span class="mr-3 ml-2">
                                    <font-awesome-icon 
                                        icon="eye"></font-awesome-icon>
                                </span>
                                <span>{{Object.values(bl.read).length}}</span>
                                <span class="ml-4 tag is-warning is-large is-rounded
                                      has-text-danger " 
                                    v-if="bl.bl_is_public !== 1">Private Post</span>

                                <span class="mr-2 ml-4">
                                    <font-awesome-icon 
                                        icon="comment-alt"></font-awesome-icon>
                                </span>
                                <span class="">
                                    {{bl.comment.length}}
                                </span>

                            </div>
                            <div class="field is-pulled-right">
                                <font-awesome-icon 
                                    icon="user"></font-awesome-icon>
                                <span class="ml-2">
                                    {{bl.user.name}}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- show slug,create date,comment,user End -->
            <!-- show excerpt START -->
            <div class="content">
                <div v-html="bl.bl_excerpt"></div>
            </div>
            <!-- show excerpt END -->
            <!-- button view,edit,delete START -->
            <div class="mb-4 mt-2 pb-6">
                <div class="field" 
                    v-if="bl.bl_is_public !== 1">
                    <span class="tag is-warning is-large is-light has-text-danger 
                        has-text-weight-bold">Private</span>
                </div>
                <div class="field is-pulled-right">
                    <button class="button is-primary is-outlined is-small 
                        is-rounded" 
                        @click.prevent="$emit('edit',bl.id)">
                        <font-awesome-icon 
                            icon="edit"></font-awesome-icon>
                        <span class="ml-2 is-uppercase">edit</span>
                    </button>
                    <button class="button is-danger is-outlined is-small 
                        is-rounded ml-2" 
                        @click.prevent="$emit('del',bl.id)">
                        <font-awesome-icon 
                            icon="trash"></font-awesome-icon>
                        <span class="ml-2 is-uppercase">delete</span>
                    </button>
                </div>
            </div>
            <!-- button view,edit,delete END -->
            <!-- show tag,category START -->
            <div class="mt-2 mb-2 pb-4">
                <div class="columns is-mobile">
                    <div class="column">
                        <div class="field">
                            <span class="tag is-medium">
                                <font-awesome-icon 
                                    icon="tag"></font-awesome-icon>
                                
                            </span>
                            <span class="tag is-medium is-primary is-light 
                            has-text-info 
                                has-text-weight-bold ml-2 " 
                                v-for="ta in bl.tag" 
                                >
                                {{ta.tag_name}}
                            </span>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field is-pulled-right">
                            <span class="mr-2">
                                <font-awesome-icon 
                                    icon="bars"></font-awesome-icon>
                            </span>
                            <span class="tag has-text-weight-bold 
                                has-text-success is-medium " 
                                v-for="ca in bl.category">
                                {{ca.cat_name}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- show tag,category END -->
        </article>
        <div class="mt-6 mb-4 d-flex justify-content-center" 
            v-if="blog.data != ''">
            <nav class="pagination is-rounded" role="navigation" aria-label="pagination">
                <a class="pagination-previous is-current">All post(s) {{blog.total}}</a>
                <a class="pagination-next is-current">page {{blog.current_page}}</a>
              <ul class="pagination-list" v-for="ln in blog.links">
                <li v-if="ln.url != null && ln.active == false">
                  <a class="pagination-link" 
                  aria-label="Page 1" aria-current="page" v-html="ln.label" 
                  @click.prevent="$emit('getBlog',ln.url)"></a>
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

        <div class="mt-4 pt-4 mb-4 pb-4">
            <div class="columns is-mobile">
                <div class="column">
                    <p class="title">Tag</p>
                    <div class="field">
                        <div class="tags">

                            <span v-for="ta in tag_has_blog" 
                                >
                                <span class="tag mr-2 is-medium has-text-info 
                                has-text-weight-bold"
                                      v-if="ta.blog.length !== 0">
                                    {{ta.tag_name}} ({{ta.blog.length}})</span>
                            </span>
                        </div>
                    </div>
                </div><!-- end of div.column -->

                <div class="column">
                    <p class="title">category</p>
                    <div class="field">
                        <div class="tags">
                            <span 
                                v-for="ca in cat_has_blog">
                                <span class="tag mr-2 is-medium 
                                has-text-primary has-text-weight-bold" 
                                    v-if="ca.blog.length !== 0">
                                    <a href="" 
                                        @click.prevent="$emit('showByCategoryId',ca.id)">
                                        {{ca.cat_name}}
                                    </a>
                                     ({{ca.blog.length}})
                                </span>
                            </span>
                        </div>
                    </div>
                </div><!-- end of div.colum -->
            </div><!-- end of div.columns -->
        </div><!-- end of div show tag,category -->

    </div>
</template>
<script>
var moment = require('moment')
export default{
    name:"BlogList",
    props:["blog","tag","category","tag_has_blog","cat_has_blog"],
    data(){return{
        moment:moment,
    }}
}

</script>
