<template>
    <div>
        <div class="box">
            <h2 class="title">visitors</h2>
        </div>
        <nav class="level">
          <div class="level-item has-text-centered">
            <div>
              <p class="heading">Today</p>
              <p class="title">{{visited_today}}</p>
            </div>
          </div>

          <div class="level-item has-text-centered">
            <div>
              <p class="heading">This Month</p>
              <p class="title">{{visit_month}}</p>
            </div>
          </div>


          <div class="level-item has-text-centered">
            <div>
              <p class="heading">This Year</p>
              <p class="title">{{visit_year}}</p>
            </div>
          </div>


          <div class="level-item has-text-centered">
            <div>
              <p class="heading">All Visitors</p>
              <p class="title">{{visit_all}}</p>
            </div>
          </div>
        </nav>

        <nav class="level">
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">you</p>
                    <p class="title">{{your_ip}}</p>
                </div>
                
            </div>

            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Browser</p>
                    <p class="title">{{your_browser}}</p>
                </div>
                
            </div>
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">os,device</p>
                    <p>
                        <span class="tag is-medium is-info">
                            {{your_os}}
                        </span>
                        <span class="tag is-medium is-primary ml-2">
                            {{your_device}}
                        </span>
                    </p>
                </div>
                
            </div>


        </nav>

    </div>
</template>
<script>
export default{
    name:'Visitor',
    data(){
        return{
            visitor_data:[],
            visited_today:0,
            visit_month:0,
            visit_year:0,
            visit_all:0,
            your_ip:'',
            your_os:'',
            your_device:'',
            your_browser:'',
        }
    },
    mounted(){
        this.getVisitor()
    },
    methods:{
        getVisitor(){
            let url = `/api/getvisitor`
            axios.get(url)
                .then(res=>{
                    this.visitor_data = res.data.visitor_all
//                    console.log(res.data)
                    if(this.visitor_data != 0){
                        this.visit_all = res.data.visitor_all
                        this.visited_today = res.data.visited_day
                        this.visit_year = res.data.visited_year
                        this.visit_month = res.data.visited_month
                        this.your_ip = res.data.your_ip
                        this.your_os = res.data.your_os
                        this.your_device = res.data.your_device
                        this.your_browser = res.data.your_browser

                    }
                    
                })
        },
    },
}
</script>
