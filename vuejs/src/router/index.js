import Vue from "vue";
import VueRouter from "vue-router";
import Leagues from "../views/Leagues.vue";
import League from "../views/League.vue";
import NewTeam from "../views/NewTeam.vue";
import NewLeague from "../views/NewLeague.vue";
import About from "../views/About.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "about",
        component: About
    },
    {
        path: "/leagues",
        name: "leagues",
        component: Leagues
    },
    {
        path: "/league/:id/week/:week",
        name: "league_week",
        component: League
    },
    {
        path: "/team/new",
        name: "team_new",
        component: NewTeam
    },
    {
        path: "/league/new",
        name: "league_new",
        component: NewLeague
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
