<template>
    <div>
        <div class="add-project my-2">
            <div class="">
                <form @submit="addProject" class="flex">
                    <input type="text" v-model="saveProject.title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">SAVE</button>
                </form>
            </div>
        </div>
      <div v-for="(project,key) in projects" :key="key" class="border-b py-3 grid grid-cols-12 gap-4">
          <div class="col-span-11 font-bold text-slate-800 text-sm">{{ project.title }}</div>
          <div class="flex">
                <span class="text-center cursor-pointer mx-2" @click="saveProject = project">
                    <edit-icon title="Edit"/>
                </span>
                <span class="text-center cursor-pointer" @click="deleteProject(project.id,'delete')">
                    <trash-icon title="Delete"/>
                </span>
          </div>
      </div>
    </div>
</template>
<script>
import axios from 'axios'
import TrashIcon from '../Icons/TrashIcon.vue'
import EditIcon from '../Icons/EditIcon.vue'
export default {
    components: {TrashIcon, EditIcon},
    data(){

        return {
            projects: [],
            saveProject: {
                title: ''
            }
        }
    },

    methods: {

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

        async addProject(e){
            e.preventDefault()
            const saving = this.saveProject.id ? await axios.put('./api/project/'+ this.saveProject.id, this.saveProject) : await axios.post('./api/project', this.saveProject)
            this.saveProject.title = '';
            await this.getProjects()
        },

        async deleteProject(id, state){
            const del = await axios.delete('./api/project/'+ id, {
                state: state
            })

            await this.getProjects()
        },

    },

    async created(){
        await this.getProjects()
    }
}
</script>

<style>

</style>