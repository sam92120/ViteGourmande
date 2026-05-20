import controller_0 from "../ux-chartjs/controller.js";
import controller_1 from "../ux-live-component/live_controller.js";
import "../ux-live-component/live.min.css";
import controller_2 from "../../controllers/hello_controller.js";
export const eagerControllers = {"symfony--ux-chartjs--chart": controller_0, "live": controller_1, "hello": controller_2};
export const lazyControllers = {"csrf-protection": () => import("../../controllers/csrf_protection_controller.js")};
export const isApplicationDebug = true;