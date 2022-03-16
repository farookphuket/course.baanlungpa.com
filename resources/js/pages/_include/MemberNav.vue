<template>
 <header :class="{ 'is-transformed': !showNavbar }" 
     class="navbar is-fixed-top is-transparent is-spaced" >
    <div class="container">
      <div class="navbar-brand is-family-secondary">
          <router-link :to="{name:'Home'}" class="navbar-item">
              <figure class="logo">
                <img src="/img/house.png" alt="บ้านลุงป้าดอทคอม สมาชิกเพจ">
              </figure>
        </router-link>
        <a :aria-expanded="isActive" 
            :class="{ 'is-active': isActive }" role="button" 
            class="navbar-burger" aria-label="menu" data-target="collapse" 
            @click="isActive = !isActive" >
          <span aria-hidden="true" />
          <span aria-hidden="true" />
          <span aria-hidden="true" />
        </a>
      </div>
      <div id="collapse" :class="{ 'is-active': isActive }" 
          class="navbar-menu is-paddingless" >
        <nav class="navbar-end is-uppercase">
            <router-link :to="{name:'MemberDashboard'}" 
                class="navbar-item">Home</router-link>

            <router-link :to="{name:'MemberBlog'}" 
                class="navbar-item">Blog</router-link>

            <router-link to="/static-site/about-us" 
                class="navbar-item">About</router-link>

            <!-- dropdown menu copy code from :https://codepen.io/sakthig/pen/RvwVYM?editors=1100 
                on 4 Mar 2022 -->
            <div class="navbar-item has-dropdown is-hoverable" 
                v-show="isTeacher">
                <a href="" class="navbar-link">
                    Teacher
                </a>
                <div class="navbar-dropdown">
                    <div class="nested navbar-item dropdown">
                        <div class="dropdown-trigger">

                            <router-link class="navbar-item" 
                                         :to="{name:'ClassRoom'}">class room</router-link>
                            <router-link class="navbar-item" 
                                         :to="{name:'TeacherCourse'}">Course</router-link>

                            <router-link class="navbar-item" 
                                         :to="{name:'TeacherStudent'}">student</router-link>

                            <router-link class="navbar-item" 
                                         :to="{name:'Teacher'}">teacher</router-link>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end of teacher menu dropdown -->

          <router-link :to="{name:'Profile'}" 
              class="navbar-item">Profile</router-link>

          <router-link :to="{name:'LogoutPage'}" 
              class="navbar-item">Logout</router-link>
        </nav>
      </div>
    </div>
  </header>
</template>

<script>
import throttle from 'lodash/throttle'

export default {
    name:'MemberNav',
  data() {
    return {
        isActive: false,
        showNavbar: true,
        lastScrollPosition: 0,
        user_must_verify:false,
        isTeacher:false,
    }
  },
  mounted() {
    this.$nextTick(() => {
      window.addEventListener('resize', throttle(this.closeMenu, 500))
      window.addEventListener('scroll', throttle(this.hideNav, 250))
    })
      this.getTeacher()
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.closeMenu)
    window.removeEventListener('scroll', this.hideNav)
  },
  methods: {
    closeMenu() {
      this.isActive = false
    },
    hideNav() {
      const currentScrollPosition =
        window.pageYOffset || document.documentElement.scrollTop
      if (currentScrollPosition < 0) return
      if (Math.abs(currentScrollPosition - this.lastScrollPosition) < 60) return
      this.showNavbar = currentScrollPosition < this.lastScrollPosition
      this.lastScrollPosition = currentScrollPosition
      setTimeout(this.closeMenu, 250)
    },
      userVerify(){
          if(this.$cookies.get("user_must_verify") 
              && this.$cookies.get("user_must_verify") !== false){
              this.user_must_verify = true
          }
      },
      getTeacher(){
          this.isTeacher = window.lsDefault.user_is_teacher
      },

  }
}
</script>
