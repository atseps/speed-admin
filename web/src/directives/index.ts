import type { App } from "vue";
import { setupLoadingDirective } from "./loading";
import { setupAuthDirective } from "./auth";
import { setupRoleDirective } from "./role";

export function setupGlobDirectives(app: App) {
  setupAuthDirective(app);
  setupLoadingDirective(app);
  setupRoleDirective(app);
}
