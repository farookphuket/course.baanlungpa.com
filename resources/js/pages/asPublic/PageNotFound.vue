<template>
    <section class="body_content">
        <div class="container">
            <p class="title has-text-centered 
                has-text-danger">Error 404</p>
            <p class="subtitle">
                Sorry,no page found
            </p>
            <div class="box">
                <div class="mb-4 mt-4">
                    <figure class="figure justify-content-center is-2by3">
                        <img 
                            src="/img/404_1.png" 
                             alt="Sorry love,but I will wait for you at your room" 
                        class="image">
                    </figure>
                    <p class="has-text-centered">
                        sorry love! but let try another page.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
<script>


export default{
    name:"PageNotFound",
    mounted(){
        document.title = "Error 404 page not found"
    },
}
</script>
