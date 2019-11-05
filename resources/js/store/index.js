import Vue from 'vue';
import Vuex from 'vuex';
import note from './modules/note';
import toast from './modules/toast';
import db from './modules/db';
import tools from './modules/tools';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    note,
    toast,
    db,
    tools
  }
});