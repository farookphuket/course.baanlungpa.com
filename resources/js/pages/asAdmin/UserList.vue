<template>
    <div>
        <div class="table-container">
            <table class="table is-hoverable is-srtiped 
                is-bordered is-fullwidth">
                <tbody v-for="u in users.data"
                    >
                    <tr >
                        <th>name</th>
                        <td>{{u.name}}</td>
                        <td>
                            <div class="buttons">

                                <button class="button is-small is-primary" 
                                    @click.prevent="$emit('edit',u.id)">
                                    <font-awesome-icon icon="edit"></font-awesome-icon>
                                </button>
                                <button class="button is-small is-danger" 
                                    @click.prevent="$emit('del',u.id)" 
                                    :disabled="u.id == user_id">
                                    <font-awesome-icon icon="trash"></font-awesome-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>email</th>
                        <td>
                            <span class="tag is-medium mr-2">
                                {{u.email}}
                            </span>

                            <span class="tag is-medium is-success" 
                                v-if="u.email_verified_at !== null">
                                {{moment(u.email_verified_at).fromNow()}}
                            </span>
                            <span class="tag is-medium is-warning" v-else>
                                Never
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>role</th>
                        <td>
                            <ul>
                                <li v-for="ro in u.role">
                                    {{ro.role_name}}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- pagination START -->

            <div class="box d-flex justify-content-center">

                <nav class="pagination is-rounded" role="navigation" aria-label="pagination">
                    <a class="pagination-previous is-current">All user(s) {{users.total}}</a>
                    <a class="pagination-next is-current">page {{users.current_page}}</a>
                  <ul class="pagination-list" v-for="ln in users.links">
                    <li v-if="ln.url != null && ln.active == false">
                      <a class="pagination-link" 
                      aria-label="Page 1" aria-current="page" v-html="ln.label" 
                      @click.prevent="$emit('getUser',ln.url)"></a>
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
    name:"UserList",
    props:["users"],
    data(){return{
        moment:moment,
        user_id:'',
    }},
    mounted(){
        this.user_id = window.lsDefault.user_id
    },

}
</script>
