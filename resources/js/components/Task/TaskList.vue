<template>
  <div>
    <div v-for="(task,key) in tasks" :key="key" class="border-b py-3 grid grid-cols-12 gap-4 cursor-move hover:bg-gray-100 px-2" 
                                                :class="[$parent.$parent.drag.start.task?.id == task.id ? 'opacity-25':'']" draggable="true"  
            @dragstart="$parent.$parent.dragStart(task)"
            @drop="$parent.$parent.dragDrop(task, view_list == 'complete')"
            @dragend="$parent.$parent.dragEnd"
            @dragover.prevent
            @dragenter.prevent>

                <div class="col-span-11 font-bold text-slate-700 text-sm">
                    <span class="block">{{ task.title }}</span>
                    <span class="text-xs text-slate-500 font-normal">
                        {{ task.project.title }} | 
                        {{ new Date(task.created_at).toLocaleDateString('en-US',{ weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }) }}
                    </span>
                </div>

                <div class="flex">
                    <span class="text-center cursor-pointer mx-2" @click="$parent.$parent.isDone({
                        ...task,
                        isDone: view_list != 'complete'
                    })">
                        <check-circle-icon v-if="task.isDone == false" title="Completed"/>
                        <refund-icon v-if="task.isDone" title="Incompleted"/>
                    </span>

                    <span class="text-center cursor-pointer" @click="$parent.$parent.editTask(task)">
                        <edit-icon title="Edit"/>
                    </span>

                    <span class="text-center cursor-pointer" @click="$parent.$parent.deleteTask(task.id,'delete')">
                        <trash-icon title="Delete"/>
                    </span>
                </div>
    </div>


    <div v-if="tasks.length == 0" key="default" class="border-b py-5 grid grid-cols-12 gap-4 cursor-move" draggable="false"  
            @drop="$parent.$parent.dragDrop({}, view_list == 'complete')"
            @dragend="$parent.$parent.dragEnd"
            @dragover.prevent
            @dragenter.prevent>
    </div>

  </div>
</template>

<script>
import CheckCircleIcon from '../Icons/CheckCircleIcon.vue';
import EditIcon from '../Icons/EditIcon.vue';
import RefundIcon from '../Icons/RefundIcon.vue';
import TrashIcon from '../Icons/TrashIcon.vue';

export default {
    components: { CheckCircleIcon, TrashIcon, RefundIcon, EditIcon },
    props: {
        tasks: {
            default: () => [],
            type: Array
        },
        view_list: {
            type: String,
            default: () => 'incomplete'
        }
    }
}
</script>

<style>

</style>