import { ComputedRef, ref, computed, Ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

interface UserRole {
  id: string;
  name: string;
}

interface CurrentUser {
  id: number;
  is_teacher: boolean;
  email: string;
  name: string;
  role: UserRole;
}

export function currentUser(): CurrentUser {
  const props = usePage().props.auth;
  const { id, name, email, role } = props.user as any;

  return {
    id, name, email, role,
    is_teacher: props['is_techaer']
  } as CurrentUser;
}
