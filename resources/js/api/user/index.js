import request from "../request";

const RESOURCE_PATH = '/users';

export async function getUsers() {
    const lessons = await request(RESOURCE_PATH);

    return lessons.data.data;
}
