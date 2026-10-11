<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, shallowRef, watch } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Image from "@tiptap/extension-image";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";
import Placeholder from "@tiptap/extension-placeholder";
import {
    AlignCenter,
    AlignLeft,
    AlignRight,
    Bold,
    ImagePlus,
    Italic,
    Link2,
    List,
    ListOrdered,
    Quote,
    Redo2,
    Strikethrough,
    Underline,
    Undo2,
} from "@lucide/vue";
import { uploadAdminImage } from "../api";

const props = defineProps<{ modelValue: string }>();
const emit = defineEmits<{ "update:modelValue": [value: string] }>();
const editor = shallowRef<Editor>();
const imageInput = ref<HTMLInputElement | null>(null);
const imageUploading = ref(false);
const imageError = ref("");

onMounted(() => {
    editor.value = new Editor({
        extensions: [
            StarterKit,
            Underline,
            Image.configure({
                inline: false,
                allowBase64: false,
                HTMLAttributes: { class: "news-editor-image" },
            }),
            Link.configure({
                openOnClick: false,
                HTMLAttributes: { target: "_blank", rel: "noopener noreferrer" },
            }),
            TextAlign.configure({ types: ["heading", "paragraph"] }),
            Placeholder.configure({ placeholder: "Mulai tulis isi berita di sini..." }),
        ],
        content: props.modelValue || "",
        editorProps: {
            attributes: {
                class: "news-rich-editor",
                role: "textbox",
                "aria-label": "Isi berita",
                "aria-multiline": "true",
            },
        },
        onUpdate: ({ editor: currentEditor }) => emit("update:modelValue", currentEditor.getHTML()),
    });
});

watch(() => props.modelValue, (value) => {
    if (editor.value && editor.value.getHTML() !== (value || "")) {
        editor.value.commands.setContent(value || "", { emitUpdate: false });
    }
});

onBeforeUnmount(() => editor.value?.destroy());

const addLink = () => {
    const currentEditor = editor.value;
    if (!currentEditor) return;
    const previous = currentEditor.getAttributes("link").href as string | undefined;
    const url = window.prompt("Masukkan URL tautan", previous || "https://");
    if (url === null) return;
    if (!url.trim()) currentEditor.chain().focus().unsetLink().run();
    else currentEditor.chain().focus().setLink({ href: url.trim() }).run();
};

const openImagePicker = () => imageInput.value?.click();
const insertImage = async (event: Event) => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];
    input.value = "";
    if (!file) return;
    if (!["image/jpeg", "image/png", "image/webp"].includes(file.type)) {
        imageError.value = "Gunakan gambar JPG, PNG, atau WebP.";
        return;
    }
    if (file.size > 8 * 1024 * 1024) {
        imageError.value = "Ukuran gambar maksimal 8 MB.";
        return;
    }

    imageUploading.value = true;
    imageError.value = "";
    try {
        const uploaded = await uploadAdminImage(file);
        editor.value?.chain().focus().setImage({ src: uploaded.data.url, alt: file.name }).run();
    } catch (error: any) {
        imageError.value = error.message || "Gambar gagal diunggah.";
    } finally {
        imageUploading.value = false;
    }
};
</script>

<template>
    <div class="news-wysiwyg">
        <p class="news-wysiwyg-hint">Tulis isi artikel, atur format teks, dan sisipkan gambar langsung di dalam berita.</p>
        <div v-if="editor" class="news-editor-toolbar" role="toolbar" aria-label="Format isi berita">
            <button type="button" title="Tebal" aria-label="Tebal" :class="{ active: editor.isActive('bold') }" @mousedown.prevent="editor.chain().focus().toggleBold().run()"><Bold /></button>
            <button type="button" title="Miring" aria-label="Miring" :class="{ active: editor.isActive('italic') }" @mousedown.prevent="editor.chain().focus().toggleItalic().run()"><Italic /></button>
            <button type="button" title="Coret" aria-label="Coret" :class="{ active: editor.isActive('strike') }" @mousedown.prevent="editor.chain().focus().toggleStrike().run()"><Strikethrough /></button>
            <button type="button" title="Garis bawah" aria-label="Garis bawah" :class="{ active: editor.isActive('underline') }" @mousedown.prevent="editor.chain().focus().toggleUnderline().run()"><Underline /></button>
            <i aria-hidden="true"></i>
            <button type="button" title="Judul 1" aria-label="Judul 1" class="editor-heading" :class="{ active: editor.isActive('heading', { level: 1 }) }" @mousedown.prevent="editor.chain().focus().toggleHeading({ level: 1 }).run()">H1</button>
            <button type="button" title="Judul 2" aria-label="Judul 2" class="editor-heading" :class="{ active: editor.isActive('heading', { level: 2 }) }" @mousedown.prevent="editor.chain().focus().toggleHeading({ level: 2 }).run()">H2</button>
            <i aria-hidden="true"></i>
            <button type="button" title="Daftar poin" aria-label="Daftar poin" :class="{ active: editor.isActive('bulletList') }" @mousedown.prevent="editor.chain().focus().toggleBulletList().run()"><List /></button>
            <button type="button" title="Daftar bernomor" aria-label="Daftar bernomor" :class="{ active: editor.isActive('orderedList') }" @mousedown.prevent="editor.chain().focus().toggleOrderedList().run()"><ListOrdered /></button>
            <button type="button" title="Kutipan" aria-label="Kutipan" :class="{ active: editor.isActive('blockquote') }" @mousedown.prevent="editor.chain().focus().toggleBlockquote().run()"><Quote /></button>
            <i aria-hidden="true"></i>
            <button type="button" title="Rata kiri" aria-label="Rata kiri" :class="{ active: editor.isActive({ textAlign: 'left' }) }" @mousedown.prevent="editor.chain().focus().setTextAlign('left').run()"><AlignLeft /></button>
            <button type="button" title="Rata tengah" aria-label="Rata tengah" :class="{ active: editor.isActive({ textAlign: 'center' }) }" @mousedown.prevent="editor.chain().focus().setTextAlign('center').run()"><AlignCenter /></button>
            <button type="button" title="Rata kanan" aria-label="Rata kanan" :class="{ active: editor.isActive({ textAlign: 'right' }) }" @mousedown.prevent="editor.chain().focus().setTextAlign('right').run()"><AlignRight /></button>
            <i aria-hidden="true"></i>
            <button type="button" title="Sisipkan gambar" aria-label="Sisipkan gambar" :disabled="imageUploading" @mousedown.prevent="openImagePicker"><ImagePlus /></button>
            <button type="button" title="Tambahkan tautan" aria-label="Tambahkan tautan" :class="{ active: editor.isActive('link') }" @mousedown.prevent="addLink"><Link2 /></button>
            <i aria-hidden="true"></i>
            <button type="button" title="Urungkan" aria-label="Urungkan" :disabled="!editor.can().undo()" @mousedown.prevent="editor.chain().focus().undo().run()"><Undo2 /></button>
            <button type="button" title="Ulangi" aria-label="Ulangi" :disabled="!editor.can().redo()" @mousedown.prevent="editor.chain().focus().redo().run()"><Redo2 /></button>
        </div>
        <input ref="imageInput" class="news-image-input" type="file" accept="image/jpeg,image/png,image/webp" @change="insertImage" />
        <EditorContent :editor="editor" />
        <small v-if="imageError" class="news-wysiwyg-error">{{ imageError }}</small>
        <small v-else>{{ imageUploading ? "Mengunggah gambar ke artikel..." : "Pilih teks lalu klik toolbar untuk memformat. Tekan Enter untuk membuat paragraf baru." }}</small>
    </div>
</template>
