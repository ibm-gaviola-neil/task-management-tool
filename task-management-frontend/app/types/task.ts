import type { TaskValidationError } from "./validationErrors"

export interface Task {
    id: number,
    task_description: string,
    status: number,
    user_id: number,
    created_at: string,
    updated_at: string
}

export interface TaskState {
    tasks: Task[] | null,
    task : Task | null,
    dates : Dates[] | null,
    isLoading : boolean,
    errors : TaskValidationError | null,
    date: string | null,
    searchQuery : string
}

export interface DateItem {
    date_desc: string;
    date: string;
}
  
export interface Dates {
    label: string;
    exact_dates: DateItem[];
}
