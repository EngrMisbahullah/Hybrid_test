import { defineStore } from "pinia";
import { ref, computed } from "vue";
import ApiService from "../services/ApiService";
import { useAuthStore } from "../store/auth";

export const useTaskStore = defineStore("Task", () => {
    const authStore = useAuthStore();
    const headers = {
        'Authorization': `Bearer ${authStore.token}`,
    }

    const taskList = async (organization_id, per_page) => {
        const queryParams = {
            'per_page': per_page
        };
        const response = await ApiService.get(`/api/task`, {params: queryParams } ,headers);
        return response;
    };

    const taskListPaginated = async ( params = {}) => {
        const queryParams = {
            'page': params.currentPage || null,
            'search' : params.search || null,
            'sortBy': params.sortBy || null,
            'sortDirection': params.sortDirection || null,
            'inactive': params.inactive || 0,
        };

        const response = await ApiService.get(`/api/task/`, {params : queryParams} ,headers);
        return response;
    };

    const addtask = async (data) => {
        console.log('Testing.....');
        console.log(headers);
        const response = await ApiService.post("/api/task/create", data, headers);
        return response;
    }

    const edittask = async (id, data) => {
        const response = await ApiService.put(`/api/task/${id}/update`, data, headers);
        return response;
    }

    const gettaskDatabyId = async (id) => {
        const response = await ApiService.get(`/api/task/${id}/show`, headers)
        return response;
    }


    const taskDelete = async ( id ) => {
        const response = await ApiService.delete(`/api/task/${id}/delete` ,headers);
        return response;
    };



    return {
        taskList,
        taskListPaginated,
        addtask,
        edittask,
        gettaskDatabyId,
        taskDelete
    }
});
