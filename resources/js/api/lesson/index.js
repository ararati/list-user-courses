import request from "../request";

const RESOURCE_PATH = '/lessons';

export async function getLessons(page = 1, itemsPerPage = 10) {
    const lessons = await request(`${RESOURCE_PATH}?page=${page}&items_per_page=${itemsPerPage}`)
    return lessons.data;
}

export async function deleteLesson(id) {
    await request.delete(`${RESOURCE_PATH}/${id}`);
    return true;
}

export async function createLesson(lesson) {
    const createdLesson = await request.post(RESOURCE_PATH, lesson);
    return createdLesson.data.data;
}
