const config = {
    BackendHost: "http://localhost:8001/api"
}

const filterEnum = {
    NONE: 'none',
    COMPLETED: 'completed',
    PENDING: 'pending'
}

const globalState = {
    tasksFilter: filterEnum.NONE
}

const controller = {
    init: function () {
        generalView.load()
    }
}

const generalView = {
    load: function () {
        tasksView.load()
        reportsView.load()
    }
}

const tasksView = {
    init: function () {
        self = tasksView
        self.elements.taskItems.editionForms.get().hide()
        self.elements.creationForm.submitButton.get().on('click', e => {
            let taskTitle = self.elements.creationForm.titleInput.get().val()
            tasksActions.create(taskTitle)
        })
        self.elements.taskItems.checkboxes.get().on('click', e => {
            let ckb = e.currentTarget
            tasksActions.edit(ckb.dataset.taskId, ckb.dataset.taskTitle, ckb.checked)
        })
        self.elements.taskItems.deleteButtons.get().on("click", e => {
            let taskId = e.currentTarget.dataset.taskId;
            tasksActions.delete(taskId)
        })
        self.elements.taskItems.showButtons.get().on("click", e => {
            let taskId = e.currentTarget.dataset.taskId;
            tasksActions.showDetail(taskId)
        })
        self.elements.taskItems.editButtons.get().on("click", e => {
            let taskId = e.currentTarget.dataset.taskId
            tasksActions.askEdit(taskId)
        })
        self.elements.taskItems.editionForms.confirmEditButtons.get().on("click", e => {
            let taskId = e.currentTarget.dataset.taskId
            let title = self.elements.taskItems.editionForms.newTitleTexts.getFor(taskId).val()
            let completed = self.elements.taskItems.checkboxes.getFor(taskId).is(':checked')
            tasksActions.edit(parseInt(taskId), title, completed)
        })
        self.elements.taskItems.editionForms.cancelEditButtons.get().on("click", e => {
            let taskId = e.currentTarget.dataset.taskId
            tasksActions.cancelEdit(taskId)
        })
        self.elements.tasksFilter.applyButton.get().on("click", e => {
            filter = self.elements.tasksFilter.optionsSelect.get().val()
            tasksActions.applyFilter(filter)
        })
    },

    showDetail: function (task) {
        Swal.fire({
            html: `
            Id: ${task.id} <br />
            Title: ${task.title} <br />
            Completed: ${task.completed} <br />
            Created at: ${task.createdAt} <br />
            Last update at: ${task.updatedAt} <br />
            `
        })
    },

    load: function () {
        this.addCreationForm()
        this.addTasksFilter()
        tasksActions.getTasks()
    },

    reload: function () {
        this.clean()
        this.load()
    },
    clean: function () {
        this.elements.container.get().html('')
    },

    addCreationForm: function () {
        this.elements.container.get().append(this.components.creationForm.create())
    },

    addTasksFilter: function () {
        this.elements.container.get().append(this.components.tasksFilter.create(globalState.tasksFilter))
    },

    addTaskItem: function (task) {
        this.elements.container.get().append(this.components.taskItem.create(task))
    },
    showEditForm: function (id) {
        this.elements.taskItems.editionForms.getFor(id).show()
    },
    hideEditForm: function (id) {
        this.elements.taskItems.editionForms.getFor(id).hide()
    },

    getElement: function (id) {
    },

    components: {
        creationForm: {
            create: () => "<div id=\"creation-form\"><input id=\"creation-form-title\" type=\"text\" name=\"title\" /><button id=\"creation-form-submit\">Create</button></div>",
        },
        tasksFilter: {
            create: (prevOptVal) =>
                `<div id="tasks-filter">
                    <label for="tasks-filter-sel">Filter: </label>
                    <select id="tasks-filter-sel">
                        ${tasksView.components.tasksFilter.filterOption.create(filterEnum.NONE, 'None', prevOptVal)}
                        ${tasksView.components.tasksFilter.filterOption.create(filterEnum.COMPLETED, 'Completed', prevOptVal)}
                        ${tasksView.components.tasksFilter.filterOption.create(filterEnum.PENDING, 'Pending', prevOptVal)}
                    </select>
                    <button id="apply-tasks-filter-btn">Apply</button>
                </div>`,
            filterOption: {
                create: (val, text, prevOptVal) => `<option ${prevOptVal == val ? "selected" : ""} value="${val}">${text}</option>`,
            },
        },
        taskItem: {
            create: (task) =>
                `<div id='todo-item-${task.id}' class='todo-item'>
                    <input type='checkbox' ${task.completed ? "checked" : ""} id="todo-ckb-${task.id}" class='todo-ckb' data-task-id="${task.id}" data-task-title="${task.title}">
                    <span id="todo-text-${task.id}" class='todo-text'>${task.title}</span>
                    <button id="todo-show-btn-{$task.id}}" class="todo-show-btn" data-task-id="${task.id}">show</button>
                    <button id="todo-edit-btn-{$task.id}}" class="todo-edit-btn" data-task-id="${task.id}">edit</button>
                    <button id="todo-delete-btn-{$task.id}}" class="todo-delete-btn" data-task-id="${task.id}">delete</button></div>
                    <div id="edition-form-${task.id}" class="edition-form">
                        <input type="text" id="new-title-txt-${task.id}" class="new-title-txt" value="${task.title}" />
                        <button id="confirm-edit-btn-${task.id}" class="confirm-edit-btn" data-task-id="${task.id}" data-task-completed="${task.completed}">Confirm</button>
                        <button id="cancel-edit-btn-${task.id}" class="cancel-edit-btn" data-task-id="${task.id}">Cancel</button>
                </div>
            `
        },
    },
    elements: {
        container: {
            get: () => $('#tasksContainer'),
        },

        creationForm: {
            get: () => $('#creation-form'),
            titleInput: {
                get: () => $('#creation-form-title')
            },
            submitButton: {
                get: () => $('#creation-form-submit')
            }
        },
        tasksFilter: {
            optionsSelect: {
                get: () => $('#tasks-filter-sel')
            },
            applyButton: {
                get: () => $('#apply-tasks-filter-btn')
            },
        },

        taskItems: {
            get: () => $('.todo-item'),
            checkboxes: {
                get: () => $('.todo-ckb'),
                getFor: (taskId) => $(`#todo-ckb-${taskId}`),
            },
            showButtons: {
                get: () => $('.todo-show-btn')
            },
            editButtons: {
                get: () => $('.todo-edit-btn')
            },
            deleteButtons: {
                get: () => $('.todo-delete-btn')
            },
            editionForms: {
                get: () => $('.edition-form'),
                getFor: taskId => $(`#edition-form-${taskId}`),
                newTitleTexts: {
                    getFor: (taskId) => $(`#new-title-txt-${taskId}`)
                },
                confirmEditButtons: {
                    get: () => $('.confirm-edit-btn')
                },
                cancelEditButtons: {
                    get: () => $('.cancel-edit-btn')
                },
            },
        }
    }
}


const tasksClient = {
    apiURL: config.BackendHost,

    exec: function (method, resource, body = null) {
        let requestOptions = {
            method: method,
            redirect: 'follow'
        };
        if (body) {
            let headers = new Headers();
            headers.append("Content-Type", "application/json");

            requestOptions.headers = headers
            requestOptions.body = JSON.stringify(body)
        }

        let url = this.apiURL + resource;
        return fetch(url, requestOptions)
            .then(response => response.text())
            .then(body => JSON.parse(body))
            .catch(error => console.error('error', error));
    },
    create: function (title) {
        return this.exec('POST', "/todo", {title: title})
    },
    delete: function (id) {
        return this.exec('DELETE', `/todo/${id}`)
    },
    update: function (id, title, completed) {
        return this.exec('PUT', `/todo/${id}`, {
            title: title,
            completed: completed
        });
    },
    list: function (filter) {
        filterQueries = {
            [filterEnum.NONE]: '',
            [filterEnum.COMPLETED]: '?completed=1',
            [filterEnum.PENDING]: '?completed=0'
        }
        return this.exec('GET', `/todo${filterQueries[filter]}`)
    },
    show: function (id) {
        return this.exec('GET', `/todo/${id}`)
    }
}

const tasksActions = {
    getTasks: function () {
        tasksClient.list(globalState.tasksFilter)
            .then(tasks => tasks.forEach(t => tasksView.addTaskItem(t)))
            .then(tasksView.init)
    },
    showDetail: function (id) {
        tasksClient.show(id)
            .then(task => tasksView.showDetail(task))
    },
    applyFilter: function (filter) {
        globalState.tasksFilter = filter
        tasksView.reload()
    },
    create: function (title) {
        tasksClient.create(title)
            .then(_ => tasksView.reload())
    },
    askEdit: function (id) {
        tasksView.showEditForm(id)
    },
    cancelEdit: function (id) {
        tasksView.hideEditForm(id)
    },
    edit: function (id, title, completed) {
        tasksClient.update(id, title, completed)
            .then(_ => tasksView.reload())
    },
    delete: function (id) {
        tasksClient.delete(id)
            .then(_ => tasksView.reload())
    },
}

const actionFilterEnum = {
    NONE: 'none',
    CREATE: 'create',
    UPDATE: 'update',
    DELETE: 'delete',
}

const sorterEnum = {
    NONE: 'none',
    ASC: 'asc',
    DESC: 'desc',
}

const reportsView = {
    load: function () {
        let generalReport = this.elements.container.generalReport
        let actionFilterSelect = generalReport.actionFilter.select
        let actSelEl = actionFilterSelect.get()
            actSelEl.append(actionFilterSelect.options.create(actionFilterEnum.NONE, 'None'))
            actSelEl.append(actionFilterSelect.options.create(actionFilterEnum.CREATE, 'Create'))
            actSelEl.append(actionFilterSelect.options.create(actionFilterEnum.UPDATE, 'Update'))
            actSelEl.append(actionFilterSelect.options.create(actionFilterEnum.DELETE, 'Delete'))
        let sorterSelect = generalReport.tasksSorter.select
        let sortSelEl = sorterSelect.get()
            sortSelEl.append(sorterSelect.options.create(sorterEnum.NONE, 'None'))
            sortSelEl.append(sorterSelect.options.create(sorterEnum.ASC, 'Asc'))
            sortSelEl.append(sorterSelect.options.create(sorterEnum.DESC, 'Desc'))
        this.init()
    },

    init: function () {
        this.elements.container.generalReport.generateButton.get().on("click", e => {
            let filter = this.elements.container.generalReport.actionFilter.select.get().val()
            let sort = this.elements.container.generalReport.tasksSorter.select.get().val()
            reportsActions.askGeneralReport(filter, sort)
        })
        this.elements.container.taskReport.generateButton.get().on("click", e => {
            taskId = this.elements.container.taskReport.taskIdInput.get().val()
            reportsActions.askTaskReport(taskId)
        })
    },

    showReport: function (report) {
        Swal.fire({
            html: reportsView.components.reportTable.create(report),
        })
    },

    components: {
        reportTable: {
            create: (report) =>
                `<table>
    <tr>
        <th>Task ID</th>
        <th>Action</th>
        <th>Description</th>
        <th>Performed at</th>
        </tr>
        ${report.map(record => reportsView.components.reportTable.record.create(record))}
</table>`,
            record: {
                create: (record) => `
            <tr>
            <td>${record.taskId}</td>
            <td>${record.action}</td>
            <td>${record.description}</td>
            <td>${record.performedAt}</td>
</tr>`
            }
        },
    },

    elements: {
        container: {
            get: () => $('#reportsContainer'),
            generalReport: {
                actionFilter: {
                    select: {
                        get: () => $('#rep-action-filter-sel'),
                        options: {
                            create: (action, desc) =>
                                `<option value="${action}">${desc}</option>`,
                        },
                    }
                },
                tasksSorter: {
                    select: {
                        get: () => $('#rep-task-sorter-sel'),
                        options: {
                            create: (action, desc) =>
                                `<option value="${action}">${desc}</option>`,
                        }
                    }
                },
                generateButton: {
                    get: () => $('#gen-rep-btn'),
                }
            },
            taskReport: {
                taskIdInput: {
                    get: () => $('#task-rep-tid-input')
                },
                generateButton: {
                    get: () => $('#task-rep-btn')
                }
            },
        },
    },
}

const reportsClient = {
    apiURL: config.BackendHost,

    exec: function (resource) {
    let requestOptions = {
        method: 'GET',
        redirect: 'follow'
    };

    let url = this.apiURL + resource;
    return fetch(url, requestOptions)
        .then(response => response.text())
        .then(body => JSON.parse(body))
        .catch(error => console.error('error', error));
    },
    get: function (filter, sort) {
        let params = {}
        if (actionFilterEnum.NONE !== filter) {
            params.action = filter
        }
        if (sorterEnum.NONE !== sort) {
            params.sortByTask = sorterEnum.ASC === sort ? 1 : -1
        }
        queryStr = new URLSearchParams(params).toString()
        if ('' !== queryStr) {
            queryStr = '?' + queryStr
        }

        return this.exec(`/report${queryStr}`)
    },
    getForTask: function (taskId) {
        return this.exec(`/report/${taskId}`)
    }
}

const reportsActions = {
    askGeneralReport: function (filter, sort) {
        reportsClient.get(filter, sort)
            .then(rep => reportsView.showReport(rep))
    },

    askTaskReport: function (taskId) {
        reportsClient.getForTask(taskId)
            .then(rep => reportsView.showReport(rep))
    },
}

$(document).ready(controller.init)