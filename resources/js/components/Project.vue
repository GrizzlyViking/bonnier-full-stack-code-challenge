<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-3">
                    <h4 v-text="project.name"></h4>
                </div>
                <div class="col-7">
                    <div v-text="totalTime"></div>
                </div>
                <div class="col-2">
                    <div class="text-right">
                        <button v-show="running" type="button" class="btn btn-sm btn-danger" @click.prevent="stopTimer">Stop</button>
                        <button v-show="!running" type="button" class="btn btn-sm btn-success" @click.prevent="startTimer">Start</button>
                    </div>
                </div>
            </div>
        </div>
        <table class="card-table table">
            <thead>
                <tr>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Time spent</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="entry in listOfEntries" :key="entry.id">
                    <td v-text="dateTimeString(entry.start)"></td>
                    <td v-text="dateTimeString(entry.end)"></td>
                    <td v-text="currentState(entry)"></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "Project",
    props: ['project'],
    data: () => ({
        running: false,
        startTime: 0,
        stopTime: 0,
        listOfEntries: [],
        totalTime: ''
    }),
    methods: {
        startTimer() {
            this.running = true;
            if (this.startTime === 0) {
                this.startTime = Date.now().valueOf();
            }
            this.addEntry();
        },
        stopTimer() {
            this.running = false;
            this.stopTime = Date.now().valueOf();
            this.addEntry();
        },
        addEntry() {
            axios.post('/projects/entry/upsert', {
                start: this.startTime,
                end: this.stopTime,
                project_id: this.project.id
            }).then(response => {
                this.refreshEntries();
                this.refreshProjectTime();
            });
        },
        refreshEntries() {
            axios.get('/projects/' + this.project.id + '/entries').then(response => {
                this.listOfEntries = response.data.entries;
            });
        },
        refreshProjectTime() {
            axios.get('/projects/' + this.project.id + '/totalTime').then(response => {
                this.totalTime = response.data.totalProjectTime;
            });
        },
        dateTimeString(dateTimeString) {
            let timestamp = new Date(dateTimeString);
            return timestamp.toDateString() + ' ' + timestamp.toLocaleTimeString();
        },
        currentState(entry) {
            return this.running ? 'running' : entry.humanReadable
        }
    },
    mounted() {
        this.refreshEntries();
        this.refreshProjectTime();
    }
}
</script>
