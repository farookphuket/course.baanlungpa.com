import {createWebHistory, createRouter } from "vue-router";



// as public view port
import PubView from '../pages/_include/PublicView.vue' 
import Home from '../pages/asPublic/Home';
import About from '../pages/asPublic/About';
import RegisterPage from "../pages/asPublic/RegisterPage.vue"
import LoginPage from "../pages/asPublic/LoginPage.vue"
import LogoutPage from '../pages/asPublic/LogoutPage.vue'
import PageNotFound from "../pages/asPublic/PageNotFound.vue"
import UserConfirmationFail from "../pages/asPublic/UserConfirmationFail.vue"
import userHasConfirmed from "../pages/asPublic/UserHasConfirmed.vue"
import UserMustVerify from '../pages/asPublic/UserMustVerify.vue'
import ForgotPassword from "../pages/asPublic/ForgotPassword/ForgotPassword.vue"
import UserResetPassword from "../pages/asPublic/ForgotPassword/UserResetPassword.vue"
import Visitor from "../pages/asPublic/Visitor.vue"

// course 26 Feb 2022
import PubCourse from '../pages/asPublic/Course.vue'
import CourseView from '../pages/asPublic/Course/CourseView.vue'

import BlogView from "../pages/asPublic/BlogView.vue"
import StaticPage from "../pages/asPublic/StaticPage.vue"
import PubBlog from "../pages/asPublic/Comment/PubBlog.vue"


// teacher 4 Mar 2022
import TeacherView from '../pages/_include/TeacherView.vue'
import Teacher from "../pages/Teacher/Teacher.vue"
import TeacherCourse from "../pages/Teacher/Course/Course.vue"
import ClassRoom from "../pages/Teacher/ClassRoom/ClassRoom.vue"
import TeacherStudent from '../pages/Teacher/Student/Student.vue'



// as Admin view 
import AdminView from "../pages/_include/AdminView.vue" 
import AdminDashboard from "../pages/asAdmin/Dashboard.vue"
import AdminUser from "../pages/asAdmin/User.vue"

// blog components
import AdminBlog from "../pages/asAdmin/Blog/Blog.vue"
import AdminBlogComment from "../pages/asAdmin/Blog/Comment.vue"

// course Admin 
import AdminCourse from "../pages/asAdmin/Course/Course.vue"

// AdminTeacher
import AdminTeacher from "../pages/asAdmin/Teacher/Teacher.vue"

import AdminCategory from "../pages/asAdmin/Category/Category.vue"
import AdminTag from "../pages/asAdmin/Tag/Tag.vue"
import AdminComment from "../pages/asAdmin/Comment/Comment.vue"
import AdminReply from "../pages/asAdmin/Reply/Reply.vue"
import AdminBlogCategory from "../pages/asAdmin/Blog/CategoryList.vue"
import AdminAbout from '../pages/asAdmin/About/About.vue'

// as Member 
import MemberView from "../pages/_include/MemberView.vue"
import MemberDashboard from "../pages/asMember/Dashboard.vue"
import MemberBlog from "../pages/asMember/Blog/Blog.vue"
import MemberBlogCategory from "../pages/asMember/Blog/CategoryList.vue"

import Profile from "../pages/asMember/Profile/Profile.vue"

export const user_id = window.lsDefault.user_id
export const user_must_verify = window.lsDefault.user_must_verify

export const user_is_teacher = window.lsDefault.user_is_teacher

//const curPage = window.location.pathname

export const routes = [
    {
        path:'/',
        redirect:'/',
        component:PubView,
        children:[

            {
                name: 'Home',
                path: '/',
                component: Home
            },
            {
                name: 'About',
                path: '/about',
                component: About
            },
            {
                name:"PubBlog",
                path:'/blog-public',
                component:PubBlog,
            },
            {
                name:"PubCourse",
                path:'/public-course',
                component:PubCourse,
            },
            {
                name:"CourseView",
                path:"/course/:slug",
                component:CourseView,
            },

            {
                name: 'LoginPage',
                path: '/login',
                component: LoginPage,
                beforeEnter:(to,from,next)=>{
                    if(user_id){
                        window.history.back()
                    }else{
                        next()
                    }

                },
            },

            {
                name: 'RegisterPage',
                path: '/register',
                component: RegisterPage,
                beforeEnter:(to,from,next)=>{
                    if(user_id){
                        window.history.back()
                    }else{
                        next()
                    }
                },
            },
            {
                name:'ForgotPassword',
                component:ForgotPassword,
                path:'/forgotpassword'
            },
            {
                name:"userHasConfirmed",
                path:"/user-has-confirmed",
                component:userHasConfirmed,
            },
            {
                name:'UserMustVerify',
                component:UserMustVerify,
                path:'/user-must-verify'
            },

            {
                name:'UserConfirmationFail',
                component:UserConfirmationFail,
                path:'/user-confirmation-fail'
            },
            {
                name: 'Visitor',
                path: '/visitor',
                component: Visitor
            },
            {
                name:"BlogView",
                path:"/:slug",
                component:BlogView,
            }

        ],
    },
    {
        name:"StaticPage",
        path:"/static-site/:slug",
        component:StaticPage,

    },
    {
        name:"PageNotFound",
        component:PageNotFound,
        path:'/page-not-found'
    },
    {
        name:"UserResetPassword",
        component:UserResetPassword,
        path:'/user-reset-password',
        meta:{requiresGuest:true},
    },
    {

        path:'/:pathMatch(.*)*',
        beforeEnter:(to,from,next)=>{
            next({name:'PageNotFound'})   
        },
    },
    {
        path:'/:pathMatch(course)',
        redirect:PubCourse,
    },

    {
        name:"Profile",
        component:Profile,
        path:'/profile',
        beforeEnter:(to,from,next)=>{
            if(!user_id){
                next({name:'LoginPage'})
            }else{
                if(user_must_verify){
                    next({name:'UserMustVerify'})
                }else{
                    next()
                }
            }
        },
    },

    /* ======== #Admin route START =============*/
    {
        path:'/admin',
        component:AdminView,
        redirect:'/dashboard-admin',
        children:[
            {

                name:'AdminDashboard',
                path:'/dashboard-admin',
                component:AdminDashboard,
            },
            {

                name:'AdminAbout',
                path:'/static-about',
                component:AdminAbout,
            },
            {

                name:'AdminUser',
                path:'/user',
                component:AdminUser,
            },
            {

                name:'AdminBlog',
                path:'/manage-blog',
                component:AdminBlog,
                
            },
            {
                name:"AdminBlogComment",
                path:"/blog-comment",
                component:AdminBlogComment
            },

            {

                name:'BlogCategory',
                path:'/category/:category_id',
                component:AdminBlogCategory
            },
            {
                name:'AdminCourse',
                path:'/admin-course',
                component:AdminCourse,
            },
            {
                name:'AdminTeacher',
                path:'/admin-teacher',
                component:AdminTeacher,
            },
            {

                name:'AdminComment',
                path:'/comment',
                component:AdminComment,
            },
            {

                name:'AdminReply',
                path:'/reply',
                component:AdminReply,
            },

            {

                name:'AdminTag',
                path:'/tag',
                component:AdminTag,
            },
            {

                name:'AdminCategory',
                path:'/category',
                component:AdminCategory,
            },
            
        ],
        beforeEnter:(to,from,next)=>{
            if(!user_id){
                next({name:'LoginPage'})
            }else{
                next()
            }
        },
    },
    /* ======== Admin route END =============*/

    /* logout page */
    {
        name:"LogoutPage",
        path:"/logout",
        component:LogoutPage,
        beforeEnter:(to,from,next)=>{
            if(!user_id){
                next({name:'Home'})
            }else{
                next()
            }
        },
    },

    /* ======== #Member route START ============ */
    {
        path:'/member',
        component:MemberView,
        redirect:'/member-dashboard',
        children:[
            {
                name:"MemberDashboard",
                path:'/member-dashboard',
                component:MemberDashboard,
            },
            {
                name:"MemberBlog",
                path:'/member-blog',
                component:MemberBlog,
            },
            {
                name:"MemberBlogCategory",
                path:"/by-category/:category_id",
                component:MemberBlogCategory
            }
        ],
        beforeEnter:(to,from,next)=>{
            if(!user_id){
                next({name:'LoginPage'})
            }else{
                if(user_must_verify){
                    next({name:'UserMustVerify'})
                }else{
                    next()
                }
            }
        },
        
    },
    /* ======== Member route END  ============ */

    /* ======== #Teacher created at 4 Mar 2022 route START */
    {

        path:'/teacher',
        component:TeacherView,
        redirect:'/teacher-dashboard',
        children:[
            {
                name:"Teacher",
                path:'/teacher-dashboard',
                component:Teacher,
            },
            {
                name:"TeacherCourse",
                path:'/teacher-course',
                component:TeacherCourse
            },

            {
                name:"ClassRoom",
                path:'/teacher-classroom',
                component:ClassRoom
            },
            {
                name:"TeacherStudent",
                path:'teacher-student',
                component:TeacherStudent,
            },
        ],
        beforeEnter:(to,from,next)=>{
            if(!user_id){
                next({name:'LoginPage'})
            }else{
                if(!user_is_teacher){
                    next({name:'MemberDashboard'})
                }else{
                    next()
                }
            }

        },
    },
    /* ======== #Teacher route END */

];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});


router.beforeEach((to,from,next)=>{

    document.title = to.name
    next()
})


export default router;
