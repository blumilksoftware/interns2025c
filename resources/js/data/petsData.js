export const dogs = [
  { breed: 'Labrador Retriever' },
  { breed: 'German Shepherd' },
  { breed: 'Golden Retriever' },
  { breed: 'Bulldog' },
  { breed: 'Poodle' },
  { breed: 'Beagle' },
  { breed: 'Rottweiler' },
  { breed: 'Yorkshire Terrier' },
  { breed: 'Dachshund' },
  { breed: 'Boxer' },
]

export const cats = [
  { breed: 'British Shorthair' },
  { breed: 'Siamese' },
  { breed: 'Maine Coon' },
  { breed: 'Sphynx' },
  { breed: 'Persian' },
  { breed: 'Ragdoll' },
  { breed: 'Bengal' },
  { breed: 'Russian Blue' },
  { breed: 'Norwegian Forest' },
  { breed: 'Scottish Fold' },
]

export const bestMatches = [
  {
    id: 1,
    name: 'Buddy',
    breed: 'Labrador Retriever',
    age: '2 years',
    status: 'Available',
    gender: 'male',
    imageUrl: '/Images/cat-dog.png',
    description: 'Friendly and energetic companion.',
    tags: ['friendly', 'playful', 'active'],
  },
  {
    id: 2,
    name: 'Luna',
    breed: 'Siamese',
    age: '1 year',
    status: 'Available',
    gender: 'female',
    imageUrl: '/Images/cat-dog.png',
    description: 'Gentle and smart.',
    tags: ['gentle', 'smart', 'calm'],
  },
]

export const samplePetImages = Array.from({ length: 100 }, (_, i) => {
  const id = i + 1
  return { id, imageUrl: `https://placedog.net/500?id=${id}` }
})
