import { createWebHistory, createRouter } from "vue-router";
import datatableUtil from "../utils/datatableUtil";

const routes = [
  {
    path: '/',
    name: "Default Route"
  },
  {
    path: '/login',
    component: () => import("../components/LoginComponent.vue"),
    name: "Login",
  },
  {
    path: '/dashboard',
    component: () => import("../components/Dashboard.vue"),
    name: "Dashboard",
    meta: { user_type: ['admin'] }
  },
  {
    path: '/sales',
    component: () => import("../components/sales/List.vue"),
    name: "Sales",
    meta: { user_type: ['admin'] }
  },

  //POS
  {
    path: '/point-of-sale',
    component: () => import("../components/pos/Main.vue"),
    name: "PointOfSale",
  },

  //category
  {
    path: '/categories/list',
    component: () => import("../components/category/List.vue"),
    name: "CategoryList",
  },

  //Unit
  {
    path: '/units/list',
    component: () => import("../components/unit/List.vue"),
    name: "UnitList",
  },

  //customer
  {
    path: '/customers/list',
    component: () => import("../components/customer/List.vue"),
    name: "CustomerList",
  },

  //items
  {
    path: '/items/list',
    component: () => import("../components/item/List.vue"),
    name: "ItemList",
  },

  //item-types
  {
    path: '/item-types/list',
    component: () => import("../components/item-type/List.vue"),
    name: "ItemTypesList",
  },

  //suppliers
  {
    path: '/suppliers/list',
    component: () => import("../components/supplier/List.vue"),
    name: "SupplierList",
  },

  //Not Found
  {
    path: '/notFound',
    component: () => import("../components/NotFound.vue"),
    name: "NotFound"
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  linkActiveClass: 'active',
});

router.beforeEach((to, from, next) => {
  datatableUtil.resetAll();
  next();
});

export default router;