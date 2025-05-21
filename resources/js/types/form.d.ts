export interface FormField {
    type: string;
    name: string;
    label: string;
    placeholder?: string;
    required?: boolean;
    options?: Array<{ label: string; value: string }>; // for select/checkboxes
}

export interface Form {
    id?: number;
    title: string;
    method: string;
    action: string;
    fields: FormField[];
    created_at?: string;
    updated_at?: string;
}
