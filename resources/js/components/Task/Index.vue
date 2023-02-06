<template>
  <div>
    <div class="projects-dropdown">
        <div class="grid grid-cols-12 gap-2 my-2">

            <div class="col-span-10">

                <select v-model="projectId" name="" id="" class="shadow appearance-none border rounded w-full py-2 px-3 
                                                        text-gray-700 leading-tight 
                                                        focus:outline-none focus:shadow-outline">
                <option :value="null">Select Project</option>
                <option v-for="(project,key) in projects" :value="project.id" :key="key">
                    {{ project.title }}
                </option>
            </select>

            </div>

            <div class="col-span-2 mt-2">
                <a href="javascript:void(0)" @click="openModal = true; saveTask = {projectId};" class="bg-green-500 hover:bg-green-700 mt-2 text-white font-bold py-2 px-4 rounded w-full">CREATE TASK</a>
            </div>

        </div>
    </div>
    
    <accordion :expand="true">
      <template v-slot:title>
        <span class="font-semibold text-xl">
            Incomplete ({{ tasks.filter((item) => item.isDone != true && item.deleted_at == null && (projectId ? projectId == item.projectId : true)).length }})
        </span>
      </template>
      <template v-slot:content>
        <task-list class="mx-2" :tasks="tasks.filter((item) => item.isDone != true && item.deleted_at == null && (projectId ? projectId == item.projectId : true))" 
                                :view_list="'incomplete'"/>
      </template>
    </accordion>

    <accordion :expand="true" class="mt-5">
      <template v-slot:title>
        <span class="font-semibold text-xl">
            Completed ({{ tasks.filter((item) => item.isDone == true && item.deleted_at == null && (projectId ? projectId == item.projectId : true)).length }})
        </span>
      </template>
      <template v-slot:content>
        <task-list class="mx-2" :tasks="tasks.filter((item) => item.isDone == true  && item.deleted_at == null && (projectId ? projectId == item.projectId : true))" 
                                :view_list="'complete'"/>
      </template>
    </accordion>


    <!-- <accordion :expand="true" class="mt-5">
      <template v-slot:title>
        <span class="font-semibold text-xl">
            Trash ({{ tasks.filter((item) => item.deleted_at != null).length }})
        </span>
      </template>
      <template v-slot:content>
        <task-list class="mx-2" :tasks="tasks.filter((item) => item.deleted_at != null)" 
                                :view_list="'trash'"/>
      </template>
    </accordion> -->


    <dialog-modal :show="openModal" @close="openModal = null">
        <template #title>
            {{ this.saveTask.id ? 'Edit':'Add' }} Task
        </template>
        <template #content>

            <div key="default">
                            <form>

                                    <div class="mb-1">
                                        <primary-label  for="task_project" value="Project"/>
                                        <select v-model="saveTask.projectId" class="shadow appearance-none border rounded w-full py-2 px-3 
                                                        text-gray-700 leading-tight 
                                                        focus:outline-none focus:shadow-outline" 
                                                id="username" type="text" placeholder="project">
                                                <option :value="null">Select Project</option>
                                                <option v-for="(project,key) in projects" :value="project.id" :key="key">
                                                    {{ project.title }}
                                                </option>
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <primary-label  for="task_title" value="Title"/>
                                        <input v-model="saveTask.title" class="shadow appearance-none border rounded w-full py-2 px-3 
                                                        text-gray-700 leading-tight 
                                                        focus:outline-none focus:shadow-outline" 
                                                id="title" type="text" placeholder="">
                                    </div>

                                    <div class="mb-1">
                                        <primary-label  for="task_description" value="Description"/>
                                        <textarea v-model="saveTask.description" class="shadow appearance-none border rounded w-full py-2 px-3 
                                                        text-gray-700 leading-tight 
                                                        focus:outline-none focus:shadow-outline" 
                                                id="description"></textarea>
                                    </div>

                                    <div class="mb-1">
                                        <primary-label  for="task_priority" value="Priority (0 means high - 1 means low)"/>
                                        <input v-model.number="saveTask.priority" class="shadow appearance-none border rounded w-full py-2 px-3 
                                                        text-gray-700 leading-tight 
                                                        focus:outline-none focus:shadow-outline" 
                                                id="priority" type="text" placeholder="">
                                    </div>
                                
                            </form>
            </div>

        </template>

        <template #footer>
                <secondary-button @click="openModal = null">
                    Cancel
                </secondary-button>

                <primary-button v-if="!saveTask.id" class="ml-2" :disabled="false" @click="storeTask">
                    Save
                </primary-button>
                <primary-button v-if="saveTask.id"  class="ml-2" :disabled="false" @click="storeTask">
                    Update
                </primary-button>
        </template>

    </dialog-modal>

  </div>
</template>

<script>
import axios from 'axios';
import Accordion from '../Common/Accordion.vue';
import TaskList from './TaskList.vue';
import DialogModal from '../Common/DialogModal.vue';
import PrimaryButton from '../Common/PrimaryButton.vue';
import SecondaryButton from '../Common/SecondaryButton.vue';
import PrimaryLabel from '../Common/PrimaryLabel.vue';

export default {
  components: { Accordion, TaskList, DialogModal, PrimaryButton, SecondaryButton, PrimaryLabel },

    data(){
        
        return {
            tasks: [],
            projects: [],
            projectId: null,

            drag: {
                start: {
                    task: null
                },
            },

            openModal: false,
            saveTask: {},
        }
    },

    methods: {
        async getTasks(){
            try {
                const task = await axios.get('./api/task?show=all')
                if(task.data.success == true && task.data.data){
                    this.tasks = task.data.data
                }else{
                    console.log('something went wrong', task)
                }
            } catch (error) {
                console.log(error)
            }
        },

        async getProjects(){

            try {
                const project = await axios.get('./api/project')
                if(project.data.success == true && project.data.data){
                    this.projects = project.data.data
                }else{
                    console.log('something went wrong', task)
                }
            } catch (error) {
                console.log(error)
            }

        },

        async isDone(task){
            const upDate = await axios.put('./api/task/' + task.id,task)

            await this.getTasks()
        },

        async deleteTask(id, state){
            const del = await axios.delete('./api/task/'+ id, {
                state: state
            })

            await this.getTasks()
        },

        async storeTask(){
            const saveData = this.saveTask.id ? await axios.put('/api/task/' + this.saveTask.id, this.saveTask) : 
                                                await axios.post('/api/task', this.saveTask)
            this.saveTask = {}
            this.openModal = false
            await this.getTasks()
        },

        async editTask(task){
            this.openModal = true
            this.saveTask = task
        },

        dragStart(task){
            this.drag.start = {
                task: task
            }
        },

        dragDrop(task, isDone){
            this.tasks = this.tasks.filter((item) => item.id != this.drag.start.task.id)
            this.drag.start.task.isDone = isDone
            let key = this.tasks.indexOf(task)
            key = key > -1 ? key : 0
            this.tasks.splice(key,0,this.drag.start.task)
            this.drag.start = {
                task: null
            }
        },

        async dragEnd(){
            const items = this.tasks.map((item,index) => {
                return {
                    id: item.id,
                    priority: index,
                    isDone: item.isDone
                }
            })

            const upDate = await axios.put('./api/task/batch',{
                batch_update: items
            })

            await this.getTasks()
        },

    },

    async created(){
        await this.getTasks()
        await this.getProjects()
    }
}
</script>

<style>

</style>