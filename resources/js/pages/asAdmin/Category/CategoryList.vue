<template>
    <div>
        <div class="box" v-if="category.data == ''">
            <p class="title has-text-danger">
                There is no data yet!
            </p>
        </div>
       <article class="box mt-2 mb-2" 
            v-for="ca in category.data"
           v-else>
           <div>
               <p class="title">{{ca.cat_name}}</p>
               <p class="subtitle">
                   <span class="mr-2">
                       <font-awesome-icon 
                           icon="user"></font-awesome-icon>
                   </span>
                   <span class="mr-2">
                        {{ca.user.name}}
                   </span>
                </p>
           </div>

           <div class="mt-2 mb-4">
               <div class="columns">
                   <div class="column">
                       <div class="field ">
                           <span class="mr-2">
                               <font-awesome-icon 
                                   icon="calendar-week"></font-awesome-icon>
                           </span>
                           <span>
                               {{moment(ca.created_at).format("YYYY-MM-DD")}}
                           </span>
                           <span class="ml-2">
                               {{moment(ca.created_at).fromNow()}}
                           </span>
                           <span class="tag has-text-success 
                               has-text-weight-bold" 
                               v-if="ca.is_allow_edit !== 0">Allow user edit
                           </span>
                             <span class="tag has-text-warning 
                             has-text-weight-bold" v-else>Not Allow</span>
                       </div>
                   </div>
                   <div class="column">
                       <div class="field is-pulled-right">
                           <button class="button is-rounded is-primary 
                               is-small is-outlined" 
                               @click.prevent="$emit('edit',ca.id)">
                               <font-awesome-icon 
                                   icon="edit"></font-awesome-icon>
                           </button>

                           <button class="button is-rounded is-danger 
                               is-small is-outlined ml-2" 
                               @click.prevent="$emit('del',ca.id)">
                               <font-awesome-icon 
                                   icon="trash"></font-awesome-icon>
                           </button>
                       </div>
                   </div>
               </div>
           </div>

       </article>
        <!-- pagination START -->

            <div class="box">

                <nav class="pagination is-rounded" role="navigation" aria-label="pagination">
                    <a class="pagination-previous is-current">All Category(s) {{category.total}}</a>
                    <a class="pagination-next is-current">page {{category.current_page}}</a>
                  <ul class="pagination-list" v-for="ln in category.links">
                    <li v-if="ln.url != null && ln.active == false">
                      <a class="pagination-link" 
                      aria-label="Page 1" aria-current="page" v-html="ln.label" 
                      @click.prevent="$emit('getCategory',ln.url)"></a>
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
var moment = require('moment')
export default{
    name:"CategoryList",
    props:["category"],
    data(){return{
        moment:moment,
    }}
}
</script>
