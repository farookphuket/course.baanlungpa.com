<template>
    <div>
        <p class="title">
            select the student :
        </p>
        <p class="subtitle">
            you can leave this un-check
        </p>
        <div class="columns is-mobile is-multiline">
            <div class="column" 
                v-for="st in students.data">
                <p>
                    <label class="checkbox" for="">
                        <span class="mr-2">
                            {{st.name}}
                        </span>
                    </label>
                </p>
            </div>
        </div>

    </div>
</template>
<script>
export default{
    name:"UserList",
    props:["students"],
}

</script>
