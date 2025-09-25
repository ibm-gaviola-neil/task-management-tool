export interface LoginValidationError {
    email?: string | [],
    password?: string,
    message?: string,
    status?: number
}

export interface TaskValidationError {
    task_description : string,
}