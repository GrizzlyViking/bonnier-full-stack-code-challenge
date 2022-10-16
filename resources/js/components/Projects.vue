<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-10">
                        <h4>Projects</h4>
                    </div>
                    <div class="col-2">
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-success" @click.prevent="addProject">Add project</button>
                        </div>
                    </div>
                </div>
            </div>
            <table class="card-table table">
                <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Entries</th>
                    <th>Total time</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(project, index) in list" :key="project.name">
                        <td v-text="project.name"></td>
                        <td v-text="getEntries(project)"></td>
                        <td v-text="project.totalProjectTime"></td>
                        <td class="text-right">
                            <button type="button" class="btn btn-sm btn-dark" @click.prevent="editProject(project)">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger" @click.prevent="deleteProject(project, index)">Delete</button>
                            <a :href="`/projects/${project.id}`" class="btn btn-sm btn-secondary">Details</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <add-project @added="refreshProjects" ref="add"></add-project>
        <edit-project @projectUpdated="refreshProjects" ref="edit"></edit-project>
    </div>
</template>

<script>
import AddProject from "./AddProject";
import EditProject from "./EditProject";

export default {
    name: "Projects",
    components: {
        'add-project': AddProject,
        'edit-project': EditProject
    },
    props: ['projects'],
    data() {
        return {
            list: this.projects
        }
    },
    methods: {
        addProject() {
            this.$refs.add.open();
        },
        editProject(project) {
            this.$refs.edit.open(project);
        },
        deleteProject(project, index) {
            axios.post('/projects/delete', {
                id: project.id,
            });
            this.list.splice(index, 1);
        },
        refreshProjects() {
            axios.get('/projects').then(response => {
                this.list = response.data.projects;
            });
        },
        getEntries(project) {
            return project.entries ? project.entries.length : 0;
        }
    }
}
</script>
