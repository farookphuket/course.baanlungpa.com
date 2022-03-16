<template>
 <header :class="{ 'is-transformed': !showNavbar }" 
     class="navbar is-fixed-top is-transparent is-spaced" >
    <div class="container">
      <div class="navbar-brand is-family-secondary">
          <router-link :to="{name:'Home'}" class="navbar-item">
              <figure class="logo">
                <img src="/img/house.png" alt="บ้านลุงป้าดอทคอม แอดมินเพจ">
              </figure>
        </router-link>
        <a :aria-expanded="isActive" :class="{ 'is-active': isActive }" 
            role="button" class="navbar-burger" aria-label="menu" 
            data-target="collapse" @click="isActive = !isActive" >
          <span aria-hidden="true" />
          <span aria-hidden="true" />
          <span aria-hidden="true" />
        </a>
      </div>
      <div id="collapse" :class="{ 'is-active': isActive }" 
          class="navbar-menu is-paddingless" >
        <nav class="navbar-end">
            <router-link :to="{name:'AdminDashboard'}" 
                class="navbar-item">Home</router-link>

          <router-link :to="{name:'AdminAbout'}" 
              class="navbar-item">About</router-link>



          <router-link :to="{name:'AdminUser'}" 
              class="navbar-item">User</router-link>



          <div class="navbar-item has-dropdown is-hoverable">
              <a class="navbar-link" href="">
                  Category & Blog
              </a>
              <div class="navbar-dropdown">
                  <div class="nested navbar-item dropdown">
                      <div class="dropdown-trigger">

                          <router-link :to="{name:'AdminCategory'}" 
                              class="navbar-item">Category</router-link>

                          <router-link :to="{name:'AdminBlog'}" 
                              class="navbar-item">Blog</router-link>


                          <router-link :to="{name:'AdminTag'}" 
                              class="navbar-item">Tag</router-link>

                          <router-link :to="{name:'AdminComment'}" 
                              class="navbar-item">Comment</router-link>

                          <router-link :to="{name:'AdminReply'}" 
                              class="navbar-item">Reply</router-link>

                      </div>
                  </div>
              </div>
             



          </div>

          <div class="navbar-item has-dropdown is-hoverable">
              <a class="navbar-link" href="">
                  Course & Teacher
              </a>
              <div class="navbar-dropdown">
                  <div class="nested navbar-item dropdown">
                      <div class="dropdown-trigger">

                          <router-link :to="{name:'AdminCourse'}" 
                              class="navbar-item">Course</router-link>

                          <router-link :to="{name:'AdminTeacher'}" 
                              class="navbar-item">Teacher</router-link>
                      </div>
                  </div>
              </div>

          </div>

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
    name:'AdminNav',
  data() {
    return {
      isActive: false,
      showNavbar: true,
      lastScrollPosition: 0
    }
  },
  mounted() {
    this.$nextTick(() => {
      window.addEventListener('resize', throttle(this.closeMenu, 500))
      window.addEventListener('scroll', throttle(this.hideNav, 250))
    })
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
    }
  }
}
</script>
