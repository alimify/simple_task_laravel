<template>
    
    <div class="app-body">
        <div class="navigation flex border-b">
            <a href="javascript:void(0)" class="px-10 py-4 border-r font-bold" :class="[tab == 'task' ? 'bg-slate-200' :'']" @click="switchTab('task')">Task</a>
            <a href="javascript:void(0)" class="px-10 py-4 border-r font-bold" :class="[tab == 'project' ? 'bg-slate-200' :'']" @click="switchTab('project')">Project</a>
        </div>
        <div class="container">
            <component :is="tab"/>
        </div>
    </div>

</template>


<script>
import Project from "./components/Project/Index.vue";
import Task from "./components/Task/Index.vue";

export default {
    components: {
        Task,
        Project
    },
    data(){

        return {
            tab: 'task'
        }
    },

    methods: {
        switchTab(tab){
            this.tab = tab
        }
    }
}
</script>